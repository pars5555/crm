<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRM</title>
<link rel="icon" type="image/svg+xml" href="{$SITE_PATH}/img/favicon.svg">
<meta name="theme-color" content="#111a2b">
{if !empty($ns.assetCss) && !empty($ns.assetJs)}
{* bundled build (bin/build_assets.php): 2 cached files instead of ~150 requests *}
<link href="{$SITE_PATH}/assets/{$ns.assetCss}" type="text/css" rel="stylesheet">
<script type="text/javascript" src="{$SITE_PATH}/assets/{$ns.assetJs}"></script>
{else}
<link href="{$SITE_PATH}/css/out/styles.css" type="text/css" rel="stylesheet prefetch">
<script type="text/javascript" src="{$SITE_PATH}/js/out/ngs.js?{$VERSION}"></script>
<script type="text/javascript" src="{$SITE_PATH}/js/out/ngs_loads.js?{$VERSION}"></script>
<script type="text/javascript" src="{$SITE_PATH}/js/out/ngs_actions.js?{$VERSION}"></script>
{/if}