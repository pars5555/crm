<?php
/**
 * Bundles the CSS and JS of the CRM into two hashed, long-cacheable files.
 *
 * The page used to pull ~150 separate files (126 scripts through document.write, 17 chained CSS @imports).
 * This script asks the RUNNING app for its own loader lists (so the order is exactly what the framework computes),
 * concatenates the files in that order and writes:
 *     htdocs/assets/app.<hash>.css, htdocs/assets/app.<hash>.js, htdocs/assets/manifest.json
 * IndexLoad uses the bundle only while every listed source file is OLDER than the bundle, so forgetting to
 * rebuild after editing a file simply falls back to the original loaders (never stale code).
 * To switch bundling off: delete htdocs/assets/manifest.json.
 *
 * usage:  php bin/build_assets.php <base-url-of-the-running-app> <path-to-htdocs>
 * e.g.    php bin/build_assets.php http://127.0.0.1:8085 /opt/crm/app/crm/htdocs
 */
if (PHP_SAPI !== 'cli') { exit(1); }
if ($argc < 3) { fwrite(STDERR, "usage: php build_assets.php <base-url> <htdocs-path>\n"); exit(2); }
$base = rtrim($argv[1], '/');
$root = rtrim(str_replace('\\', '/', realpath($argv[2])), '/');
if (!$root || !is_dir($root)) { fwrite(STDERR, "htdocs path not found\n"); exit(2); }

function fail($msg) { fwrite(STDERR, "BUILD FAILED: $msg\n"); exit(1); }
function fetch($url) {
    $ctx = stream_context_create(array('http' => array('timeout' => 60, 'ignore_errors' => true)));
    $r = @file_get_contents($url, false, $ctx);
    if ($r === false || $r === '') { fail("cannot fetch $url"); }
    return $r;
}
/** normalise ../ and ./ of an absolute url path */
function norm($path) {
    $out = array();
    foreach (explode('/', $path) as $seg) {
        if ($seg === '' || $seg === '.') { continue; }
        if ($seg === '..') { array_pop($out); continue; }
        $out[] = $seg;
    }
    return '/' . implode('/', $out);
}

// ---------- CSS ----------
$cssLoader = fetch($base . '/css/out/styles.css');
if (!preg_match_all('~@import\s+url\("([^"]+)"\);~', $cssLoader, $m)) { fail('css loader has no @import lines: ' . substr($cssLoader, 0, 120)); }
$cssFiles = array();
foreach ($m[1] as $u) {
    $p = parse_url($u, PHP_URL_PATH);                       // /devout/css/skin.css
    if (strpos($p, '/devout/') !== 0) { fail("unexpected css url $u"); }
    $cssFiles[] = substr($p, strlen('/devout/'));            // css/skin.css
}
$css = '@charset "UTF-8";' . "\n";
$sources = array();
foreach ($cssFiles as $rel) {
    $file = "$root/$rel";
    if (!is_file($file)) { fail("css source missing: $rel"); }
    $sources[] = $rel;
    $txt = file_get_contents($file);
    $txt = preg_replace('~^\xEF\xBB\xBF~', '', $txt);        // BOM
    $txt = preg_replace('~@charset\s+"[^"]*";~i', '', $txt);
    $dirUrl = '/devout/' . dirname($rel) . '/';              // how the browser resolved relative urls in dev mode
    $txt = preg_replace_callback('~url\(\s*(["\']?)(.*?)\1\s*\)~s', function ($mm) use ($dirUrl) {
        $u = trim($mm[2]);
        if ($u === '' || preg_match('~^(data:|https?:|//|#|/(?!devout/))~i', $u)) { return $mm[0]; }
        $suffix = '';
        if (preg_match('~^([^?#]*)([?#].*)$~', $u, $x)) { $u = $x[1]; $suffix = $x[2]; }
        $abs = norm((strpos($u, '/') === 0 ? '' : $dirUrl) . $u);
        if (strpos($abs, '/devout/') === 0) { $abs = substr($abs, strlen('/devout')); }
        return 'url("' . $abs . $suffix . '")';
    }, $txt);
    $css .= "\n/* ==== $rel ==== */\n" . $txt . "\n";
}

// ---------- JS ----------
$js = '';
foreach (array('ngs.js', 'ngs_loads.js', 'ngs_actions.js') as $loaderName) {
    $loader = fetch($base . '/js/out/' . $loaderName);
    if (!preg_match_all('~src="([^"]+)"~', $loader, $m)) { fail("js loader $loaderName has no script lines: " . substr($loader, 0, 120)); }
    foreach ($m[1] as $u) {
        $p = parse_url($u, PHP_URL_PATH);                    // /js/lib/jquery.js
        if (!$p || strpos($p, '//') === 0) { fail("unexpected js url $u"); }
        $rel = ltrim($p, '/');
        $file = "$root/$rel";
        if (!is_file($file)) { fail("js source missing: $rel"); }
        $sources[] = $rel;
        $txt = file_get_contents($file);
        $txt = preg_replace('~^\xEF\xBB\xBF~', '', $txt);
        $js .= "\n/* ==== $rel ==== */\n" . $txt . "\n;\n";
    }
}
foreach (array('css/builder.json', 'js/builder.json') as $rel) { if (is_file("$root/$rel")) { $sources[] = $rel; } }

// ---------- write ----------
$dir = "$root/assets";
if (!is_dir($dir) && !@mkdir($dir, 0755, true)) { fail('cannot create assets dir'); }
$cssName = 'app.' . substr(md5($css), 0, 10) . '.css';
$jsName = 'app.' . substr(md5($js), 0, 10) . '.js';
if (file_put_contents("$dir/$cssName", $css) === false || file_put_contents("$dir/$jsName", $js) === false) { fail('cannot write bundles'); }
$manifest = array('css' => $cssName, 'js' => $jsName, 'built_at' => time(), 'sources' => array_values(array_unique($sources)));
$tmp = "$dir/manifest.json.tmp";
file_put_contents($tmp, json_encode($manifest, JSON_UNESCAPED_SLASHES));
rename($tmp, "$dir/manifest.json");
// keep the 3 newest builds of each kind (pages opened during a deploy may still ask for the previous names)
foreach (array('css', 'js') as $ext) {
    $old = glob("$dir/app.*.$ext");
    usort($old, function ($a, $b) { return filemtime($b) - filemtime($a); });
    foreach (array_slice($old, 3) as $f) { @unlink($f); }
}
printf("OK: %d css files -> %s (%d KB), %d js files -> %s (%d KB)\n", count($cssFiles), $cssName, round(strlen($css) / 1024),
    count($sources) - count($cssFiles) - 2, $jsName, round(strlen($js) / 1024));
exit(0);
