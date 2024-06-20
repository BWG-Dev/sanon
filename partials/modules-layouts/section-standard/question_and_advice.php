<div class="container advice-section">
    <div class="row question-row">
        <div class="col-6 question">
            <div class="gradient-primary ">
                <h1 class="body"><?php the_sub_field('question'); ?>
                </h1>
            </div>
        </div>
        <div class="col-6 advice">
            <div class="advice-inner">
                <ul>
                    <?php if (have_rows('advice')): ?>
                    <?php while (have_rows('advice')): the_row(); ?>
                    <li>
                        <?php the_sub_field('advice_statement'); ?>
                    </li>
                    <?php endwhile; endif;  ?>
                </ul>
            </div>
        </div>
    </div>

</div>