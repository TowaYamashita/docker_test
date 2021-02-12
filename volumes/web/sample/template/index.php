<?= $header ?>

<div class="container">
    <h1>
        <?= $title ?>
    </h1>

    <ul class="list-group mt-md-3">
        <form method="post" id="finishForm">
            <input type="hidden" name="mode" value="finish">
            <input type="hidden" name="id" value="">
        </form>
        <form method="post" id="deleteForm">
            <input type="hidden" name="mode" value="delete">
            <input type="hidden" name="id" value="">
        </form>
        <?php foreach($todoList as $todo): ?>
            <li class="list-group-item<?php if($todo['finished']): ?> list-group-item-dark<?php endif;?>">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                        <?= $todo['title'] ?>
                    </h5>
                    <h5 class="mb-1">
                        <?= $todo['finished_at'] ?>
                    </h5>
                    <div>
                        <?php if(!$todo['finished']): ?>
                            <button type="button" class="btn btn-danger code-delete-todo" value="<?= $todo['id'] ?>">
                                削除
                            </button>
                            <button type="button" class="btn btn-secondary code-finish-todo" value="<?= $todo['id'] ?>">
                                完了
                            </button>
                        <?php endif;?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <form action="index.php" method="post">
        <input type="hidden" name="mode" value="create">
        <div class="form-group  mt-md-3">
            <label for="title">
                TODO(Within 140 characters)
            </label>
            <input type="text" class="form-control" id="title" name="title" max="140" required>
        </div>
        <div class="form-group  mt-md-3">
            <label for="deadline">
                Deadline
            </label>
            <input type="datetime-local" id="deadline" name="deadline" step="3600" required>
        </div>
        <button type="submit" class="btn btn-primary">
            Submit
        </button>
    </form>

</div>

<?= $script ?>

<?= $footer ?>
