<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation">
    <ul class="pagination">
    <?php if ($pager->hasPrevious()) : ?>
        <li>
            <a class="btn btn-outline-secondary btn-sm text-white" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                <span aria-hidden="true"><?= lang('Pager.first') ?></span>
            </a>
        </li>
        <li>
            <a class="btn btn-outline-secondary btn-sm text-white" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                <span aria-hidden="true"><?= lang('Pager.previous') ?></span>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link): ?>
        <li <?= $link['active'] ? 'class="bg-primary"' : '' ?>>
            <a  class="btn btn-primary btn-sm text-white" href="<?= $link['uri'] ?>">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li>
            <a  class="btn btn-outline-secondary btn-sm text-white" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
                <span aria-hidden="true"><?= lang('Pager.next') ?></span>
            </a>
        </li>
        <li>
            <a  class="btn btn-outline-secondary btn-sm text-white" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                <span aria-hidden="true"><?= lang('Pager.last') ?></span>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>