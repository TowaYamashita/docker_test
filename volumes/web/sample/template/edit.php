<?= $header ?>

<div class="container">
    <h1>
        <?= $title ?>
    </h1>

    <form action="index.php" method="post">
        <input type="hidden" name="mode" value="update">
        <input type="hidden" name="id" value="<?= $todoList['id'] ?>">
        <div class="form-group  mt-md-3">
            <label for="title">
                Title(Within 140 characters)
            </label>
            <input type="text" class="form-control" id="title" name="title" max="140" value="<?php echo $todoList['title'] ?>" required>
        </div>
        <div class="form-group  mt-md-3">
            <label for="deadline">
                Deadline
            </label>
            <input type="datetime-local" id="deadline" name="deadline" step="600" value="<?php echo str_replace(" ", "T", $todoList['finished_at']) ?>" equired>
        </div>
        <button type="submit" class="btn btn-primary">
            更新
        </button>
    </form>

</div>

<?= $script ?>

<?= $footer ?>
