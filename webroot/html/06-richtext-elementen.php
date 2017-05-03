<!doctype html>
<html lang="nl" class="no-js">
<!--<![endif]-->
<head>
    <?php include_once('blocks/head.html'); ?>
</head>
<body>

<?php include_once('blocks/sidebuttons.html'); ?>
<div class="navigation navigation__slide-down">
    <?php include_once('blocks/header.html'); ?>
</div>

<?php include_once('blocks/breadcrumbs.html'); ?>

<section>
    <div class="wrapper">
        <div class="inner">

            <div class="block block--six block--six__center">
                <h1>Standaard richtext elementen</h1>

                <div class="richtext">
                    <p>Unordered list:</p>
                    <ul>
                        <li>List item 1</li>
                        <li>List item 2</li>
                        <li>List item 3</li>
                    </ul>

                    <p>Ordered list:</p>
                    <ol>
                        <li>List item 1</li>
                        <li>List item 2</li>
                        <li>List item 3</li>
                    </ol>

                    <br/>
                    <h1>Heading 1</h1>
                    <h2>Heading 2</h2>
                    <h3>Heading 3</h3>
                    <h4>Heading 4</h4>
                    <h5>Heading 5</h5>
                    <h6>Heading 6</h6>

                    <br/>
                    <p>Lorem ipsum dolor sit amet, consectetur <a href="#" title="title">adipisicing elit</a>.
                        Accusamus accusantium aliquid blanditiis cupiditate iste natus nihil vero? Consequatur,
                        consequuntur debitis dolore, expedita illo in ipsam officia provident quis suscipit
                        voluptas.</p>

                    <p><strong>Strong</strong></p>
                    <p><strong>Bold</strong></p>
                    <p><em>Emphasized</em></p>
                    <p><i>Italic</i></p>
                    <p><u>Underline</u></p>
                    <address>Address</address>

                    <hr>

                    <br/>
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aliquid
                        autem deleniti dolores enim eos error facere fugiat impedit, iure, labore laboriosam,
                        maxime non nulla provident quisquam sit veritatis! Tenetur.
                    </blockquote>

                    <pre>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus
                        architecto aspernatur consectetur ea earum eligendi error excepturi expedita fuga,
                        hic ipsa itaque minima nam, nihil nobis, odit reiciendis repellendus tenetur.
                    </pre>
                    
                    <table>
                        <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Age</th>
                        </tr>
                        <tr>
                            <td>Jill</td>
                            <td>Smith</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>Eve</td>
                            <td>Jackson</td>
                            <td>94</td>
                        </tr>
                        <tr>
                            <td>John</td>
                            <td>Doe</td>
                            <td>80</td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>

    </div>

</section>

<?php include_once('blocks/footer.html'); ?>
</body>
</html>