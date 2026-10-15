<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

    use crm\loads\AdminLoad;
    use crm\security\RequestGroups;
    use NGS;

    class IndexLoad extends AdminLoad {

        public function load() {
            $pageName = NGS()->getRoutesEngine()->getPackage();
            $userId = NGS()->getSessionManager()->getUserId();
            if ($userId>0){
            $this->addParam('sticky_note', \crm\managers\StickyNoteManager::getInstance()->getPageContent($pageName, $userId));
            }
            $this->addParam('sticky_note_page_name', $pageName);
            $this->loadBundledAssets();
        }

        /**
         * Bundled CSS/JS built by bin/build_assets.php: one cached file each instead of ~150 requests.
         * The bundle is used only while every source file it was built from is older than the bundle;
         * otherwise the page falls back to the original loaders, so a forgotten rebuild never serves stale code.
         */
        private function loadBundledAssets() {
            $root = NGS()->getPublicDir();
            $manifestFile = $root . '/assets/manifest.json';
            if (!is_file($manifestFile)) {
                return;
            }
            $m = json_decode(file_get_contents($manifestFile), true);
            if (!is_array($m) || empty($m['css']) || empty($m['js']) || empty($m['sources']) || empty($m['built_at'])
                || !is_file($root . '/assets/' . $m['css']) || !is_file($root . '/assets/' . $m['js'])) {
                return;
            }
            clearstatcache();
            foreach ($m['sources'] as $source) {
                $path = $root . '/' . $source;
                if (!is_file($path) || filemtime($path) > $m['built_at']) {
                    return;
                }
            }
            $this->addParam('assetCss', $m['css']);
            $this->addParam('assetJs', $m['js']);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/index.tpl";
        }

    }

}
