<?php
    $background = esc_html(get_option('vspfw_background_image'));

    if (empty($background)) {
        $background = '#f1f1f1';
    } else {
        $background = "url($background)";
    }
?>

<style>
    body {
        background: <?php echo $background ?>;
    }
    #vspw {
        top:25%;
        left: 50%;
        transform: translate3d(-50%,-25%, 0);
        position: absolute;
        background: #FFF;
        border: 1px solid #e3e3e3;
        border-radius: 5px;
        text-align: center;
        padding: 1em 3em;

    }
    div#vspw-request-password {
        color: #6b6b6b;
        position: absolute;
        bottom: -50px;
    }
    #vspw input[type="submit"] {
        background: #3079ff;
        padding: 7px 15px;
        color: #FFF;
        border: 0;
        font-size: 17px;
        cursor: pointer;
    }
    #vspw input[type="submit"]:hover {
        background:#6ca0ff;
    }
    #vspw input[type="password"] {
        padding:5px 10px;
    }
    div#vspw-enter-password-string {
        margin-bottom: 10px;
    }
    div#vspw-request-password {
        color: #6b6b6b;
        bottom: -30px;
        position: relative;
        height: 0;
    }
</style>