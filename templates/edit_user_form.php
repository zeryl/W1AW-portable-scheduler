<form action="edit_user.php" method="post">
    <fieldset>
        <div class="control-group">
            <input name="id" type="hidden" value="<?= $id?>"/>
        </div>
        <div class="control-group">
            <input name="call" placeholder="Call sign" type="text" value="<?= $call?>"/>
        </div>
        <div class="control-group">
            <input name="name" placeholder="Name" type="text" value="<?= $name?>"/>
        </div>
        <div class="control-group">
            <input autofocus name="email" placeholder="Email" type="text" value="<?= $email?>"/>
        </div>
        <div class="control-group">
            <input name="phone" placeholder="Phone" type="text" value="<?= $phone?>"/>
        </div>
        <div class="control-group">
            <input name="new_password" placeholder="New password" type="password"/>
        </div>
        <div class="control-group">
            <input name="confirmation" placeholder="New password again" type="password"/>
        </div>
        <div class="control-group">
            <button type="submit" class="btn">Save</button>
        </div>
    </fieldset>
</form>