<h2>Create task</h2>
<form method="post">
    <input type="hidden" name="id">
    <div class="id-parent">
        <div class="id-child">
            <table class="table table-sm form-table">
                <tbody>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email"></td>
                </tr>
                <tr>
                    <td>Task description:</td>
                    <td><input type="text" name="description"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input class="btn btn-secondary" type="submit" name="createTask" value="Сохранить">
</form>

<span id='message'>
    <?php if (isset($message)): ?>
        <b><?php echo $message; ?></b>
    <?php endif; ?>
</span>
