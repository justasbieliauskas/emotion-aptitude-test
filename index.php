<?php
$messages = include 'src/messages.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Žinutės</title>
        <link rel="stylesheet" media="screen" type="text/css" href="css/screen.css" />
    </head>
    <body>
        <div id="wrapper">
            <h1>Jūsų žinutės</h1>
            <form method="post">
                <p class="err">
                    <label for="fullname">Vardas, pavardė *</label><br/>
                    <input id="fullname" type="text" name="fullname" value="" />
                </p>
                <p>
                    <label for="birthdate">Gimimo data *</label><br/>
                    <input id="birthdate" type="text" name="birthdate" value="" />
                </p>
                <p>
                    <label for="email">El.pašto adresas</label><br/>
                    <input id="email" type="text" name="email" value="" />
                </p>
                <p class="err">
                    <label for="message">Jūsų žinutė *</label><br/>
                    <textarea id="message"></textarea>
                </p>
                <p>
                    <span>* - privalomi laukai</span>
                    <input type="submit" value="Skelbti" />
                    <img src="img/ajax-loader.gif" alt="" />
                </p>
            </form>
            <ul>
                <? if(empty($messages)): ?>
                    <li>
                        <strong>Šiuo metu žinučių nėra. Būk pirmas!</strong>
                    </li>
                <? else: ?>
                    <? foreach($messages as $message): ?>
                        <li>
                            <span><? echo $message['created_at']; ?></span> 
                            <? if($message['email'] !== null): ?>
                                <a href="mailto:<? echo $message['email']; ?>"><? echo $message['name']; ?></a>,
                            <? else: ?>
                                <? echo $message['name']; ?>,
                            <? endif; ?>
                            <? echo $message['age'] ?> m.
                            <br/>
                            <? echo $message['content']; ?>
                        </li>
                    <? endforeach ?>
                <? endif; ?>
            </ul>
            <p id="pages">
                <a href="#" title="atgal">atgal</a>
                <a href="#" title="1">1</a>
                2
                <a href="#" title="3">3</a>
                <a href="#" title="4">4</a>
                <a href="#" title="toliau">toliau</a>
            </p>
        </div>
    </body>
</html>
