<form method="POST" action="/short" id="form">
    <input type="text" name="user_url" value="<?= htmlspecialchars($_POST["user_url"] ?? '') ?>">
    <button type="submit">Сократить</button>
</form>
<?php if (isset($_SESSION["error"])) : ?>
    <p><?= htmlspecialchars($_SESSION["error"]) ?></p>
    <?php unset($_SESSION["error"]); ?>
<?php elseif (isset($_SESSION["new_url"])) : ?>
    <div class="cont">
        <p id="text"><a href="<?= htmlspecialchars($_SERVER["REQUEST_SCHEME"] . "://" . $newUrl) ?>" target="_blank"><?= htmlspecialchars($_SESSION["new_url"]) ?></a></p>
        <div id="copy-button" onclick="copy()">Копировать</div>
    </div>
    <?php unset($_SESSION["new_url"]); ?>
<?php endif; ?>