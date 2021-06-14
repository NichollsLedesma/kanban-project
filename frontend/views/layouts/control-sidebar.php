<aside class="control-sidebar control-sidebar-dark">
    <?php
    echo \hail812\adminlte\widgets\Menu::widget([
        'items' => [
            ['label' => 'Board settings', 'header' => true],
            ['label' => 'Manage users',  'icon' => 'users-cog', 'options' => ['id' => 'board-manage-users']],
            ['label' => 'Leave board', 'icon' => 'running', 'options' => ['id' => 'user-leave-board']],
            ['label' => 'Remove board', 'icon' => 'trash', 'options' => ['id' => 'remove-board', 'class' => 'btn-remove']],
        ],
    ]);
    ?>
</aside>