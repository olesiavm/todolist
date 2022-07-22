<form method="post">
	<div class="id-parent">
    	<div class="id-child">
			<table class="table table-sm form-table">
				<input type="hidden" name="id" value="<?php echo $task['id']; ?>"><br>
				<thead class="thead-dark">
				    <tr>
				      <th scope="col">№</th>
				      <th scope="col">Name</th>
				      <th scope="col">Task</th>
				      <th scope="col">Daescription</th>
				      <th scope="col">Status</th>
				    </tr>
				</thead>
				<tbody>
				    <?php if (isset($task)): ?>
				        <tr>
                            <th scope="row"><input type="hidden" name="id" value="<?php echo $task['id']; ?>"></th>
                            <td><input type="text" name="name" value="<?php echo $task['name']; ?>"></td>
                            <td><input type="text" name="email" value="<?php echo $task['email']; ?>"></td>
                            <td><input type="text" name="description" value="<?php echo $task['description']; ?>"></td>
                            <td>
                                <select name="status">
                                    <?php if ($task['status'] == 1): ?>
                                        <option value="1" selected>Выполненная задача</option>
                                    <?php else: ?>
                                        <option value="1">Выполненная</option>
                                    <?php endif; ?>
                                    <?php if ($task['status'] == 0): ?>
                                        <option value="0" selected>Не выполненная задача</option>
                                    <?php else: ?>
                                        <option value="0">Не выполненная</option>
                                    <?php endif; ?>
                                </select>
                            </td>
				        </tr>
				    <?php endif; ?>
				    <tr>
						<td></td>
						<td><input type="submit" name="editTask" value="Сохранить"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>

<?php if (isset($message)): ?>
	<b><?php echo $message; ?></b>
<?php endif; ?>
