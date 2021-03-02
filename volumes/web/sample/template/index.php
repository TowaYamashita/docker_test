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

    <div class="card border-dark">
        <div class="card-header">
            表示オプション
        </div>
        <div class="card-body">
            <form method="get">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        並び替え
                    </label>
                    <div class="col-sm-10">
                        <div class="btn-toolbar">
                            <div class="btn-group btn-group-toggle mr-2" data-toggle="buttons">
                                <label class="btn btn-outline-dark active">
                                    <input type="radio" name="sort_key" value="created_at" autocomplete="off" checked> TODOの作成日
                                </label>
                                <label class="btn btn-outline-dark">
                                    <input type="radio" name="sort_key" value="finished_at" autocomplete="off"> TODOの期限
                                </label>
                            </div>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-dark active">
                                    <input type="radio" name="sort_order" value="asc" autocomplete="off" checked> 昇順
                                </label>
                                <label class="btn btn-outline-dark">
                                    <input type="radio" name="sort_order" value="desc" autocomplete="off"> 降順
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        表示するTODO
                    </label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="checked_status_to_be_displayed[]" value="todo" checked>
                            <label class="form-check-label">
                                未完了
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="checked_status_to_be_displayed[]" value="doing" checked>
                            <label class="form-check-label">
                                着手
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="checked_status_to_be_displayed[]" value="done" checked>
                            <label class="form-check-label">
                                完了
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-redo"></i>
                            適用する
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <ul class="list-group mt-md-3">
        <form method="post" id="changeStatus">
            <input type="hidden" name="mode" value="change_status">
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
            <li class="list-group-item<?php if($todo['status'] === 'done'): ?> list-group-item-dark<?php endif;?>">
                <div class="row">
                    <div class="col-sm-2">
                        <?= $todo['status'] === 'todo' ? '未完了' : '' ?>
                        <?= $todo['status'] === 'doing' ? '着手' : '' ?>
                        <?= $todo['status'] === 'done' ? '完了' : '' ?>
                    </div>
                    <div class="col-sm-4 text-truncate">
                        <?= htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $todo['finished_at'] ?>
                    </div>
                    <div class="col-sm-3">
                        <?php if($todo['status'] !== 'done'): ?>
                            <button type="button" class="btn btn-info code-edit-todo" value="<?= $todo['id'] ?>">
                                <i class="fas fa-edit"></i>
                                編集
                            </button>
                            <button type="button" class="btn btn-outline-danger code-delete-todo" value="<?= $todo['id'] ?>">
                                <i class="fas fa-trash-alt"></i>
                                削除
                            </button>
                            <button type="button" class="btn btn-success code-change-status" value="<?= $todo['id'] ?>">
                                <?php if($todo['status'] === 'todo'): ?>
                                    <i class="fas fa-fire"></i>
                                    着手
                                <?php elseif($todo['status'] === 'doing'): ?>
                                    <i class="fas fa-check"></i>
                                    完了
                                <?php endif; ?>
                            </button>
                        <?php endif;?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="container mt-3">
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
                            <i class="fas fa-plus-circle"></i>
                            新規作成
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $script ?>

<?= $footer ?>
