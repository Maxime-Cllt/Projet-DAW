<?php
include_once '../app/models/DBManage.php';
$db = new DBManage();

if (isset($_SESSION['userInfo'])) {
    include_once "../app/models/User.php";
    $user = unserialize($_SESSION['userInfo']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="/img/neptune_icon.png"/>
    <link rel="stylesheet" type="text/css" href="/css/UI_Theme.css"/>
    <link rel="stylesheet" type="text/css" href="/css/topic.css"/>
    <title>Forum</title>
</head>
<body>
<?php require_once '../app/views/navBar.php'; ?>
<div style="margin-top: 10vh">

    <div class="bouton_retour">
        <a href="/index.php?controller=forum&action=getForumView">
            <img width="25" height="25" style="margin-left: 20px; margin-top: 20px" alt="Retour" title="Retour"
                 src="/img/backto.png" class="back_button" draggable="false">
        </a>
    </div>

    <?php

    foreach ($messages as $message) {
        $image_url = $message['image_profil'];
        $pseudo_user = $message['pseudo'];
        $message_user = $message['content'];
        $idmessage = $message['idmessage'];
        $idauteur = $message['idauteur'];

        echo '<table>';
        echo '<tr>';
        echo '<td class="left_td">';

        switch ($topicid) {
            case 1:
                echo "<p title='Photo de Neptune'  style='text-align: center;'><img class='img_profil_topic' src='/img/neptune_512px.png' alt='Image de profil'></a></p>";
                echo '<h3 title="Pseudo du posteur" style="text-align: center">Neptune</h3>';
                $date_formatee = date("d/m/Y", strtotime($message['date']));
                $heure_formatee = date("H\hi:s", strtotime($message['date']));
                echo '<p style="text-align: center"> Envoyé à : ' . $heure_formatee . ' le ' . $date_formatee . ' </p>';
                echo '</td>';
                echo "<td class='right_td'>";
                echo '<h3 title="Pseudo du posteur" style="text-align: justify-all; margin-right: 5%; margin-left: 5%">' . $message_user . '</h3>';
                break;

            default:
                if ($image_url == 'default.png' or is_null($image_url) or strlen($image_url) <= 0 or !file_exists($image_url)) {
                    echo "<p title='Photo de $pseudo_user'  style='text-align: center;'><a href='/index.php?controller=user&action=userPublicPage&userid=$idauteur'><img class='img_profil_topic' src='/img/default_user.png' alt='Image de profil'></a></p>";
                } else {
                    echo "<p title='Photo de $pseudo_user'  style='text-align: center;'><a href='/index.php?controller=user&action=userPublicPage&userid=$idauteur'><img class='img_profil_topic' src='/$image_url' alt='Image de profil'></a></p>";
                }
                echo '<h3 title="Pseudo du posteur" style="text-align: center">' . $message['pseudo'] . '</h3>';
                $date_formatee = date("d/m/Y", strtotime($message['date']));
                $heure_formatee = date("H\hi:s", strtotime($message['date']));
                echo '<p style="text-align: center"> Envoyé à : ' . $heure_formatee . ' le ' . $date_formatee . ' </p>';
                echo '</td>';
                echo "<td class='right_td'>";
                echo '<h3 title="Pseudo du posteur" style="text-align: justify-all; margin-right: 5%; margin-left: 5%">' . $message_user . '</h3>';

                if (isset($user->id) and isset($message['idauteur']) and $message['idauteur'] == $user->id or isset($user->isAdmin) and $user->isAdmin) {
                    echo "<button type='submit' onclick='DeleteMessage($idmessage, $topicid)' style='width: 40px;height: 40px;' title='Supprimer le message' class='img_delete_topic' ></button>";
                }
                break;
        }


        echo "</td>";
        echo '</tr>';
    }
    echo '</table>';

    switch ($topicid) {
        case 1:
            break;

        default:
            //Si l'utilisateur est connecté on affiche le formulaire de réponse au topic
            if (isset($_SESSION['userInfo'])) {
                echo '<form action="/index.php?controller=forum&action=createPost" class="form_topic" method="post">';
                echo '<input  type="hidden" name="idtopic" value="' . $topicid . '">';
                echo "<textarea name='content' minlength='1'  title='Entrer votre message de réponse à $pseudo_user' id='content' placeholder='Votre réponse à $pseudo_user' cols='70' rows='10' required></textarea><br>";
                echo '<input type="submit" class="bouton_envoyer_message_topic" value="Envoyer">';
                echo '</form>';
            }
            break;
    }
    ?>
</div>
<div class="lien_page_login" style="padding-bottom: 15%;"></div>

<script>
    function DeleteMessage(idmessageJS, idtopicJS) {
        $.ajax({
            url: '/index.php?controller=forum&action=deleteMessage',
            type: 'POST',
            dataType: 'text',
            data: {
                idmessage: idmessageJS,
                idtopic: idtopicJS
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        })
        setTimeout(function () {
            location.reload();
        }, 20);
    }
</script>

<script src="/js/UI_Theme.js"></script>
</body>
</html>
