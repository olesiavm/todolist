<?php $byName = 'ByName'; ?>
<?php $byEmail = 'ByEmail'; ?>
<?php $byStatus = 'ByStatus'; ?>

<?php if (isset($_GET['sort'])): ?>
    <?php if ($_GET['sort'] == 'ByName'): ?>
        <?php $byName = '-ByName'; ?>
    <?php elseif ($_GET['sort'] == '-ByName'): ?>
        <?php $byName = 'ByName'; ?>
    <?php else: ?>
        <?php $byName = 'ByName'; ?>
    <?php endif; ?>

    <?php if ($_GET['sort'] == 'ByEmail'): ?>
        <?php $byEmail = '-ByEmail'; ?>
    <?php elseif ($_GET['sort'] == '-ByEmail'): ?>
        <?php $byEmail = 'ByEmail'; ?>
    <?php else: ?>
        <?php $byEmail = 'ByEmail'; ?>
    <?php endif; ?>

    <?php if ($_GET['sort'] == 'ByStatus'): ?>
        <?php $byStatus = '-ByStatus'; ?>
    <?php elseif ($_GET['sort'] == '-ByStatus'): ?>
        <?php $byStatus = 'ByStatus'; ?>
    <?php else: ?>
        <?php $byStatus = 'ByStatus'; ?>
    <?php endif; ?>
<?php endif; ?>

<a class="btn rounded theme-button add-btn" href="/create-task"><strong><i class="bi bi-plus"></i></strong></a><br><br>

<div class="id-parent">
    <div class="id-child">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col"><a href="/show-tasks?page=<?php echo $page . '&sort=' . $byName; ?>">Name</th>
                    <th scope="col"><a href="/show-tasks?page=<?php echo $page . '&sort=' . $byEmail; ?>">Email</th>
                    <th scope="col">Description</th>
                    <th scope="col"><a href="/show-tasks?page=<?php echo $page . '&sort=' . $byStatus; ?>">Status</a></th>
                    <?php if (isset($_SESSION['auth']) && ($_SESSION['roleId'] == 1)): ?>
                        <th scope="col">Изменить статус, задачу</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($tasks)): ?>
                    <?php foreach ($tasks as $row): ?>
                        <tr>
                            <th scope="row"><?php echo $row['id']; ?></th>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td>
                                <?php if ($row['status'] == 1): ?>
                                    <?php echo "Задача выполнена"; ?>
                                <?php else: ?>
                                    <?php echo "Задача не выполнена"; ?>
                                <?php endif; ?>
                                <i class="fas fa-class-name"></i>
                            </td>
                            <?php if (isset($_SESSION['auth']) && ($_SESSION['roleId'] == 1)): ?>
                                <td>
                                    <a href="/edit-task-status/<?php echo $row['id']; ?>">
                                        <i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i></a>
                                    <a href="/edit-task/<?php echo $row['id']; ?>">
                                        <i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="btn-group">
    <?php if (isset($page) && $page !== 0): ?>

        <?php if ($page > 1): ?>
            <?php if (isset($_GET['sort']) && !empty($_GET['sort'])): ?>
                <?php $backpage2 = '<a class="btn theme-button rounded" href= /show-tasks?page=1&sort=' . $_GET['sort'] . '><<</a>'; ?>
            <?php else: ?>
                <?php $backpage2 = '<a class="btn theme-button rounded" href= /show-tasks?page=1><<</a>'; ?>
            <?php endif; ?>
            <?php echo $backpage2 . " "; ?>
        <?php endif; ?>


        <?php if ($page > 2): ?>
            <?php if (isset($_GET['sort']) && !empty($_GET['sort'])): ?>
                <?php $backpage1 = '<a class="btn theme-button rounded" href= /show-tasks?page=' . ($page - 1) . '&sort=' . $_GET['sort'] . '><</a>'; ?>
            <?php else: ?>
                <?php $backpage1 = '<a class="btn theme-button rounded" href= /show-tasks?page=' . ($page - 1) . '><</a>'; ?>
            <?php endif; ?>
            <?php echo $backpage1 . " "; ?>
        <?php endif; ?>


        <b><a class="btn theme-button rounded" href="#"><?php echo " " . $page . " "; ?></a></b>


        <?php if ($page < $pageCount): ?>
            <?php if (isset($_GET['sort']) && !empty($_GET['sort'])): ?>
                <?php $nextpage1 = '<a class="btn theme-button rounded" href= /show-tasks?page=' . ($page + 1) . '&sort=' . $_GET['sort'] . '>></a>'; ?>
            <?php else: ?>
                <?php $nextpage1 = '<a class="btn theme-button rounded" href= /show-tasks?page=' . ($page + 1) . '>></a>'; ?>
            <?php endif; ?>
            <?php echo $nextpage1 . " "; ?>
        <?php endif; ?>


        <?php if ($page < $pageCount - 1): ?>
            <?php if (isset($_GET['sort']) && !empty($_GET['sort'])): ?>
                <?php $nextpage2 = '<a class="btn theme-button rounded" href= /show-tasks?page=' . $pageCount . '&sort=' . $_GET['sort'] . '>>></a>'; ?>
            <?php else: ?>
                <?php $nextpage2 = '<a class="btn theme-button rounded" href= /show-tasks?page=' . $pageCount . '>>></a>'; ?>
            <?php endif; ?>
            <?php echo $nextpage2; ?>
        <?php endif; ?>

    <?php endif; ?>
</div> 

