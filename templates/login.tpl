<div class="container">
    <h5>Admin Login</h5>
    <form action="{$SITE_PATH}/dyn/login/do_login" method="POST" autocomplete="off">
        <div class="input-field col s12">
            <input type="text" name="username"/>
        </div>
        <div class="input-field col s12">
            <input type="password" name="password"/>
        </div>
        <div class="center-align">
            <button type="submit" value="login">Login</button>
        </div>
    </form>
</div>