<?= $header ?>

<?php if($alert_message["type"] === "success"): ?>
    <div class="alert alert-success" role="alert">
        <?= $alert_message["body"] ?>
    </div>
<?php elseif($alert_message["type"] === "error") : ?>
    <div class="alert alert-danger" role="alert">
        <?= $alert_message["body"] ?>
    </div>
<?php endif;?>

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
        <form action="edit.php" method="get" id="editForm">
            <input type="hidden" name="mode" value="edit">
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
                            <button type="button" class="btn btn-info code-edit-todo" value="<?= $todo['id'] ?>">
                                編集
                            </button>
                            <button type="button" class="btn btn-outline-danger code-delete-todo" value="<?= $todo['id'] ?>">
                                削除
                            </button>
                            <button type="button" class="btn btn-success code-finish-todo" value="<?= $todo['id'] ?>">
                                完了
                            </button>
                        <?php endif;?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="container mt-5">
    <div class="card border-primary">
        <div class="card-header">
            新しいTODOの追加
        </div>
        <div class="card-body">
            <form action="index.php" method="post">
            <input type="hidden" name="mode" value="create">
            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">
                    やること(140文字以内)
                </label>
                <div class="col-sm-8">
                    <input type="textarea" class="form-control" id="title" name="title" max="140" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="finished_at" class="col-sm-4 col-form-label">
                    期限
                </label>
                <div class="col-sm-8">
                    <input type="datetime-local" id="finished_at" name="finished_at" step="600" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">
                        新規作成
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $script ?>

<?= $footer ?>
