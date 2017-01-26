/**
 * Created by sancho on 24.01.17.
 */
var Page_object = function () {
    var greetings_role_id = "[id='greetings_role']";
    var mode_id = "[id='mode']";
    var paging_id = "[id='paging']";
    var user_auth_panel_id = "[id='user_auth_panel']";
    var login_id = "[id='login']";
    var pass_id = "[id='pass']";
    var rep_pass_id = "[id='rep_pass']";
    var email_id = "[id='email']";
    var action = "";
    var url = "router.php";

    this.load = function () {
        var greetings_role = $(greetings_role_id);
        var mode = $(mode_id);
        var paging = $(paging_id);
        var user_auth_panel = $(user_auth_panel_id);
        action = "load_page";
        $.post(
            url, {
                action: action
            },
            function (data) {
                data = JSON.parse(data);
                var error = data.error;
                if (!error.isError) {
                    result = data.result;
                    greetings_role.html(result.greetings_role);
                    mode.html(result.mode);
                    paging.html(result.paging);
                    user_auth_panel.html(result.html_user_auth_panel);
                } else
                    console.log(error.message);
            }
        ).always(function () {

        });
    };

    this.registration = function () {
        var login = $(login_id).val();
        var pass = $(pass_id).val();
        var rep_pass = $(rep_pass_id).val();
        var email = $(email_id).val();
        action = "registration";
        if (pass == rep_pass) {
            $.post(
                url, {
                    action: action,
                    login: login,
                    pass: pass,
                    rep_pass:rep_pass,
                    email: email
                },
                function (data) {
                    data = JSON.parse(data);
                    var error = data.error;
                    if (!error.isError) {
                        window.location.href = "/";
                    } else
                        console.log(error.message);
                }
            ).always(function () {

            });
        } else alert("Пароли не совпадают");
    };

    this.logout = function () {
        action = "logout";
        $.post(
            url, {
                action: action
            },
            function (data) {
                data = JSON.parse(data);
                var error = data.error;
                if (!error.isError) {
                    window.location.href = "/";
                } else
                    console.log(error.message);
            }
        ).always(function () {

        });
    };

    this.logOn = function () {
        action = "logOn";
        var login = $(login_id).val();
        var pass = $(pass_id).val();

        $.post(
            url, {
                action: action,
                login: login,
                pass: pass
            },
            function (data) {
                data = JSON.parse(data);
                var error = data.error;
                if (!error.isError) {
                    window.location.href = "/";
                } else
                    console.log(error.message);
            }
        ).always(function () {

        });
    };
};

var page = new Page_object();

window.onload = function () {
    page.load();
};