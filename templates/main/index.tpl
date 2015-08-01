<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRM</title>
        <link href="{$STATIC_PATH}/css/out/styles.css" type="text/css" rel="stylesheet prefetch">
        <script type="text/javascript" src="{$STATIC_PATH}/js/out/ngs.js?{$VERSION}"></script>
        <script type="text/javascript" src="{$STATIC_PATH}/js/out/ngs_loads.js?{$VERSION}"></script>
        <script type="text/javascript" src="{$STATIC_PATH}/js/out/ngs_actions.js?{$VERSION}"></script>
    </head>
    <body>
        <header class="header">
            header
        </header>
        <section class="wrapper">
            {include file="{getTemplateDir}/main/left_menu.tpl"}
            <div class="content">
                {nest ns=content}
            </div>
        </section>
        <footer class="footer">
            footer
        </footer>
    </body>
</html>
