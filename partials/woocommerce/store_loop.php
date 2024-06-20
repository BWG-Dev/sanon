<div class="container featured-posts store-loop">

    <?php

    $posts_number = get_sub_field('number_of_posts');
    $order = get_sub_field('order');
    $cat_title = get_sub_field('category_title');
    $title = get_sub_field('loop_title');

?>

    <div class="container featured-posts store-loop">

        <div class="row featured-outer-row-first row-store-loop">
            <div class="col-12 col-lg-4 store-loop-menu-section">
                <div class="store-loop-inner">
                    <h2><?php echo $cat_title; ?>
                    </h2>
                    <div class="store-line">
                        <hr>
                    </div>
                    <div class="modules healthnews-cat-menu cat-menu-store">
                        <div class="container menu-cont menu-cont-store">
                            <span class="woo_sanon_cat">Select Category</span>
							<?php echo do_shortcode('[widget id="nav_menu-2"]'); ?>	
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-12 col-lg-8 featured-first-post-image featured-products-col">
                <div class="row loop-title">
                    <?php 
                           
            
                    ?>
                    <?php  $woocommerce_title = single_term_title(''. '', false); ?>
                    <?php


                    if ($woocommerce_title) : ?>
                    <h2><?php echo $woocommerce_title; ?>
                    </h2>
                    <?php else :
                        echo '<h2>Popular Books</h2>';
                        ?>
                    <?php    endif; ?>




                </div>
                <div class="row row-for-store">


                    <?php
                                if (woocommerce_product_loop()) {
                                    if (wc_get_loop_prop('total')) {
                                        while (have_posts()) {
                                            the_post();

                                            do_action('woocommerce_shop_loop');

                                            wc_get_template_part('content', 'product');
                                        }
                                    }

                                    woocommerce_product_loop_end();

                                    do_action('woocommerce_after_shop_loop');
                                } else {
                                    do_action('woocommerce_no_products_found');
                                }


do_action('woocommerce_after_main_content');

?>    </div>     

<?php if (is_shop()) : ?>

<div class="container shop-paragraph">
   <div class="row">
    <div class="col-12">
      <div class="paragraph-container">
        <h3>S-Anon Literature for Sale</h3>
        <p>Living with a loved one’s compulsive sexual behavior can be difficult and overwhelming, but you don’t have to
          face this challenge alone. S-Anon provides support to people who are living with or have lived with a
          sexaholic,
          regardless of whether the addict chooses their own path to recovery.</p>
        <p>S-Anon books are important tools in the S-Anon program. The literature of S-Anon is written by S-Anon members
          and offers our common experience about recovery from the family disease of sex addiction. Through our
          literature, you can learn about the S-Anon program and how its principles have transformed members’ lives. In
          S-Anon books, you will read stories of members’ experiences dealing with a loved one’s sex addiction, and how
          members have put the principles of S-Anon’s 12 Steps into practice to recover from the effects of living with
          the sex addiction of a family member or friend.</p>
        <p>If you’re interested in learning more about the S-Anon program and how it can help you cope with the
          difficult
          emotions, challenges, and trials of dealing with a loved one’s sex addiction, we encourage you to browse our
          S-Anon literature to learn more about our principles and to help you start the path to recovery.</p>
        <p>Note: S-Ateen literature is also available; the S-Ateen Fellowship welcomes young people, ages 12-19, who
          have
          been affected by the sexual behavior of someone close to them.</p>

        <h3>The Role of S-Anon Literature in Recovery</h3>
        <p>S-Anon books are an important resource for those who wish to learn how to put the principles of the S-Anon
          program into practice and begin their journey of recovery from the effects upon us of another person’s
          sexaholism.</p>
        <p>S-Anon literature is written by S-Anon members for S-Anon members and offers valuable experience about
          recovery
          from the family disease of sexaholism. Our literature focuses on the solution rather than the problem and
          explains the Twelve Step approach to recovery.</p>
        <p>S-Anon books are a helpful tool in conjunction with attending S-Anon meetings where members share about their
          common problem of being affected by another’s sex addiction and listen to the experience, strength, and hope
          of
          others. We strongly encourage anyone seeking help dealing with a loved one’s compulsive sexual behavior to
          attend S-Anon meetings; however, readers can benefit from S-Anon literature regardless of whether they have
          attended an S-Anon meeting.</p>

        <?php echo do_shortcode("[expand title='Read more' swaptitle='Read less' tag='button' trigpos='below' trigclass='btn-primary' targclass='mx-0']   
        <h3>Recommended S-Anon Books for the Newcomer</h3>
        <p>Anyone who is interested in learning more about the S-Anon program can begin by reading S-Anon literature.
          For
          those who are new to S-Anon, we recommend starting with the literature referenced below. You don’t have to
          purchase all of the books at once, and you can read them in any order, or even simultaneously! In addition, we
          encourage you to download our <a href='https://sanon.org/#welcome-packet'>free Welcome Packet</a> and/or
          attend
          an S-Anon meeting at a time and place that works for you; there is no cost for attending S-Anon meetings.</p>
        <p>The books referenced below provide details on the S-Anon Program as well as how members have worked through
          the
          12 Steps and applied their principles to the challenges of living with or having lived with a sexaholic friend
          or relative. All our S-Anon books are available through our online store and are available in both print and
          e-book formats.</p>

        <h3>We’re Glad You’re Here…Helpful Information for the Newcomer</h3>
        <p><a
            href='https://sanon.org/store/interest-categories/s-anon-category/booklets/newcomer-s-booklet-helpful-information-for-the-newcomer/'><i>We’re
              Glad You're Here… Helpful Information for the Newcomer</i></a> is a booklet meant to provide those who are
          new to S-Anon with information to assist you in determining whether you might benefit from S-Anon. The booklet
          includes an explanation of S-Anon and descriptions of commonly used words and tools of the program. In
          addition,
          the booklet outlines how S-Anon meetings and groups work.</p>

        <h3>Working the S-Anon Program</h3>
        <p>The book <a
            href='https://sanon.org/store/interest-categories/s-anon-category/books-2/working-the-s-anon-program/'><i>Working
              the S-Anon Program</i></a> shares the collective experience of how members “work” the program by examining
          attitudes and past actions, putting the principles of the S-Anon Twelve 12 Steps and 12 Traditions into
          practice. This book covers specific topics such as trust, healthy sexuality, sexaholism in a son or daughter,
          sharing information with others, and more.</p>
        <p><i>Working the S-Anon Program</i> is organized into four parts, each of which is related to an important area
          of our recovery.</p>
        <ul>
          <li><b>Part 1:</b> Describes the tools members use to begin and enhance their personal recovery.</li>
          <li><b>Part 2:</b> Contains sharing from S-Anon members on their experiences of healing and recovery in their
            relationship with the sexaholic and with others.</li>
          <li><b>Part 3:</b> Focuses on ways to carry the S-Anon message of recovery to others who are searching for
            recovery from the effects of another person’s sexaholism.</li>
          <li><b>Part 4:</b> Contains the <i>S-Anon Suggested Meeting Format and Readings</i> used during S-Anon
            meetings.
          </li>
        </ul>
        <p>Working the <i>S-Anon Program</i> is a wonderful resource and can be especially helpful If this is your first
          contact with a Twelve-Step program.</p>

        <h3>Reflections of Hope</h3>
        <p><a href='https://sanon.org/store/interest-categories/s-anon-category/books-2/reflections-of-hope/'><i>Reflections
              of Hope</i></a> is a collection of daily meditations and readings for anyone affected by another person’s
          compulsive sexual behavior. This book embraces the many diverse voices of S-Anon—members of all ages and
          genders
          who have been touched by the effects of another person’s sex addiction. This collection of readings reflects
          the
          hope that comes from putting the principles of the S-Anon 12 Steps and 12 Traditions into practice, resulting
          in
          a new understanding of ourselves and a new perspective on the disease of sexaholism and its far-reaching
          effects
          on our families, friends, and communities.</p>
        <p><i>Reflections of Hope</i> contains a reading for every day of the year. Each reading includes a descriptive
          title, a story/meditation based on experiences shared by members of S-Anon, and a quote for further
          reflection.
          A helpful index references issues you may be struggling with such as abstinence, boundaries, despair, healing,
          sexually transmitted diseases, trust, and more.</p>

        <h3>S-Anon Twelve Steps</h3>
        <p>The S-Anon Twelve Steps, adapted from the Twelve Steps of Alcoholics Anonymous, are the heart of the S-Anon
          Program. In <a
            href='https://sanon.org/store/interest-categories/s-anon-category/books-2/s-anon-twelve-steps/'><i>S-Anon
              Twelve Steps</i></a>, these Steps are outlined in detail with descriptions, member stories, and specific
          ways members have worked through some of their recovery issues. Each chapter also has questions suitable for
          individual writing and reflection or discussion with others.</p>
        <p>S-Anon Twelve Steps is a book designed to help newcomers begin to apply the principles of the Twelve Steps to
          their lives in order to recover from the effects of sexaholism in a loved one or acquaintance. We have found
          that applying these spiritual principles to our lives we can be happy, healthy, and productive, regardless of
          whether the sexaholic chooses recovery.</p>

        <h3>S-Anon Twelve Traditions</h3>
        <p><a href='https://sanon.org/store/interest-categories/s-anon-category/books-2/s-anon-twelve-traditions/'><i>S-Anon
              Twelve Traditions</i></a> is a sharing of the collective experience of S-Anon members. This book explains
          the spiritual guidelines that foster harmony and unity within our groups, throughout our worldwide fellowship,
          and in all our relationships. </p>
        <p><i>S-Anon Twelve Traditions</i> outlines each of the Twelve Traditions and includes Tradition descriptions,
          member stories, and specific examples of how members have put the principles of the Twelve Traditions into
          practice, both within the fellowship and in all aspects of their lives. Each chapter also has questions
          suitable
          for individual writing and reflection or discussion with others.</p>

        <h3>How to Use S-Anon Literature</h3>
        <p>S-Anon literature is the written experience, strength, and hope of those in recovery. Used regularly, it can
          be
          the basis for meditation and increased recovery awareness.</p>
        <p>Many members read a portion of S-Anon literature at the beginning and/or end of their day and then spend some
          time meditating or journaling about what it means to them. Others use the index of a particular piece of
          literature to find a topic that resonates with issues they are currently experiencing. Some of the S-Anon
          books
          contain questions which can be answered alone or with a sponsor or other S-Anon member. In addition, S-Anon
          literature is used during meetings to provide a starting point for sharing on the group’s chosen meeting
          topic.
        </p>
        <p>Books such as <i>S-Anon Twelve Steps or S-Anon Twelve Traditions</i> are quite helpful in learning more about
          the Steps and Traditions and how to apply their spiritual principles to our lives. While the various tools of
          the program and the fellowship itself support our recovery, we have found that study of the Steps and
          Traditions
          from an S-Anon perspective, aiming to incorporate their principles into our lives, is essential for recovering
          from the effects of sexaholism.</p>
        <p>While S-Anon books are an important tool in our recovery, we strongly encourage newcomers to also attend
          S-Anon
          meetings in order to lessen feelings of isolation, have the opportunity to identify and confirm common
          problems,
          and to hear the experience, strength, and hope of others. Meetings are a vital part of the S-Anon program and
          give us a place where we can be ourselves and be unconditionally accepted.</p>

        <h3>Additional S-Anon Literature</h3>
        <p>While the S-Anon books outlined above are a suggested place to begin for those who are new to the S-Anon
          program, additional S-Anon booklets and pamphlets are available on various topics such as sponsoring and being
          sponsored in S-Anon, service work, issues faced by couples in recovery, and hope for teenagers who have been
          affected by the sexual behavior of a relative or friend.</p>

        <h3>Formats and Availability</h3>
        <p>S-Anon books, as well as some booklets, are available in both print and e-book formats. S-Anon e-books can be
          purchased/viewed on various platforms, such as Amazon’s Kindle, and Barnes and Noble’s Nook. Printed copies of
          S-Anon literature are available through our online store at <a
            href='http://www.sanon.org/store'>www.sanon.org/store</a>.</p>
        <p>Some S-Anon literature is also available in Spanish; these pieces can also be found in our online store.</p>[/expand]") ?>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
