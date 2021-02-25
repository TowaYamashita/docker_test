<?= $header ?>

<div class="container">
    <h1>
        <?= $title ?>
    </h1>

    <div class="card border-primary">
        <div class="card-header">
            TODOの編集
        </div>
        <div class="card-body">
            <form action="index.php" method="post">
            <input type="hidden" name="mode" value="update">
            <input type="hidden" name="id" value="<?= $todoList['id'] ?>">
            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">
                    やること(140文字以内)
                </label>
                <div class="col-sm-8">
                    <input type="textarea" class="form-control" id="title" name="title" max="140" value="<?= htmlspecialchars($todoList['title'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="finished_at" class="col-sm-4 col-form-label">
                    期限
                </label>
                <div class="col-sm-8">
                    <input type="datetime-local" id="finished_at" name="finished_at" step="600" value="<?= str_replace(" ", "T", $todoList['finished_at']) ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">
                        状態
                </label>
                <div class="col-sm-8">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-dark <?= ($todoList['status'] === 'todo') ? 'active' : '' ?>">
                            <input type="radio" name="status" value="todo" autocomplete="off" <?= ($todoList['status'] === 'todo') ? 'checked' : '' ?>> 未完了
                        </label>
                        <label class="btn btn-outline-dark <?= ($todoList['status'] === 'doing') ? 'active' : '' ?>">
                            <input type="radio" name="status" value="doing" autocomplete="off" <?= ($todoList['status'] === 'doing') ? 'checked' : '' ?>> 着手
                        </label>
                        <label class="btn btn-outline-dark <?= ($todoList['status'] === 'done') ? 'active' : '' ?>">
                            <input type="radio" name="status" value="done" autocomplete="off" <?= ($todoList['status'] === 'done') ? 'checked' : '' ?>> 完了
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        更新
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="location.href='index.php'">
                        <i class="fas fa-undo-alt"></i>
                        戻る
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $script ?>

<?= $footer ?>
