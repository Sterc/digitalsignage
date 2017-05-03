<!doctype html>
<html lang="nl" class="no-js">
<head>
	<?php include_once('blocks/head.html') ?>

    <style>
        .list-group {
            margin-top: 15%;
            margin-bottom: 20px;
            padding-left: 0;
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 10px 15px;
            margin-bottom: -1px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
        }

        .list-group-item:first-child {
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
        }

        .list-group-item:last-child {
            margin-bottom: 0;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .list-group-item > .badge {
            float: right;
        }

        .list-group-item > .badge + .badge {
            margin-right: 5px;
        }

        a.list-group-item {
            color: #555555;
        }

        a.list-group-item .list-group-item-heading {
            color: #333333;
        }

        a.list-group-item:hover,
        a.list-group-item:focus {
            text-decoration: none;
            color: #555555;
            background-color: #f5f5f5;
        }

        .list-group-item.disabled,
        .list-group-item.disabled:hover,
        .list-group-item.disabled:focus {
            background-color: #eeeeee;
            color: #777777;
        }

        .list-group-item.disabled .list-group-item-heading,
        .list-group-item.disabled:hover .list-group-item-heading,
        .list-group-item.disabled:focus .list-group-item-heading {
            color: inherit;
        }

        .list-group-item.disabled .list-group-item-text,
        .list-group-item.disabled:hover .list-group-item-text,
        .list-group-item.disabled:focus .list-group-item-text {
            color: #777777;
        }

        .list-group-item.active,
        .list-group-item.active:hover,
        .list-group-item.active:focus {
            z-index: 2;
            color: #ffffff;
            background-color: #428bca;
            border-color: #428bca;
        }

		.list-group-item.active .list-group-item-heading,
		.list-group-item.active:hover .list-group-item-heading,
		.list-group-item.active:focus .list-group-item-heading,
		.list-group-item.active .list-group-item-heading > small,
		.list-group-item.active:hover .list-group-item-heading > small,
		.list-group-item.active:focus .list-group-item-heading > small,
		.list-group-item.active .list-group-item-heading > .small,
		.list-group-item.active:hover .list-group-item-heading > .small,
		.list-group-item.active:focus .list-group-item-heading > .small {
			color: inherit;
		}

		.list-group-item.active .list-group-item-text,
		.list-group-item.active:hover .list-group-item-text,
		.list-group-item.active:focus .list-group-item-text {
			color: #e1edf7;
		}

		.list-group-item-success {
			color: #3c763d;
			background-color: #dff0d8;
		}

		a.list-group-item-success {
			color: #3c763d;
		}

		a.list-group-item-success .list-group-item-heading {
			color: inherit;
		}

		a.list-group-item-success:hover,
		a.list-group-item-success:focus {
			color: #3c763d;
			background-color: #d0e9c6;
		}

		a.list-group-item-success.active,
		a.list-group-item-success.active:hover,
		a.list-group-item-success.active:focus {
			color: #fff;
			background-color: #3c763d;
			border-color: #3c763d;
		}

		.list-group-item-info {
			color: #31708f;
			background-color: #d9edf7;
		}

		a.list-group-item-info {
			color: #31708f;
		}

		a.list-group-item-info .list-group-item-heading {
			color: inherit;
		}

        a.list-group-item-info:hover,
        a.list-group-item-info:focus {
            color: #31708f;
            background-color: #c4e3f3;
        }

		a.list-group-item-info.active,
		a.list-group-item-info.active:hover,
		a.list-group-item-info.active:focus {
			color: #fff;
			background-color: #31708f;
			border-color: #31708f;
		}

		.list-group-item-warning {
			color: #8a6d3b;
			background-color: #fcf8e3;
		}

		a.list-group-item-warning {
			color: #8a6d3b;
		}

		a.list-group-item-warning .list-group-item-heading {
			color: inherit;
		}

		a.list-group-item-warning:hover,
		a.list-group-item-warning:focus {
			color: #8a6d3b;
			background-color: #faf2cc;
		}

		a.list-group-item-warning.active,
		a.list-group-item-warning.active:hover,
		a.list-group-item-warning.active:focus {
			color: #fff;
			background-color: #8a6d3b;
			border-color: #8a6d3b;
		}

		.list-group-item-danger {
			color: #a94442;
			background-color: #f2dede;
		}

		a.list-group-item-danger {
			color: #a94442;
		}

		a.list-group-item-danger .list-group-item-heading {
			color: inherit;
		}

		a.list-group-item-danger:hover,
		a.list-group-item-danger:focus {
			color: #a94442;
			background-color: #ebcccc;
		}

		a.list-group-item-danger.active,
		a.list-group-item-danger.active:hover,
		a.list-group-item-danger.active:focus {
			color: #fff;
			background-color: #a94442;
			border-color: #a94442;
		}

		.list-group-item-heading {
			margin-top: 0;
			margin-bottom: 5px;
		}

		.list-group-item-text {
			margin-bottom: 0;
			line-height: 1.3;
		}

		a.logo {
			background-color: #DF006B;
		}

		.logo img {
			margin: 0 auto;
		}

		a.logo:hover {
			background-color: #DF002B;
		}
	</style>

</head>

<body>

<div class="container">
	<div class="row">
		<div class="col-sm-4 col-sm-push-4">
			<div class="list-group">
				<a href="#" class="list-group-item logo-index"><img src="../assets/img/logo.svg" class="img-responsive"
															  title="Logo project"/></a>
				<?php
				if ($handle = opendir('.')) {
					$options = "";
					$list    = [];
					while (false !== ($entry = readdir($handle))) {
						$ext = pathinfo($entry, PATHINFO_EXTENSION);
						if ($entry != "." && $entry != ".." && $entry != 'index.php' && $ext == 'php') {
							$list[] = $entry;
						}
					}
					closedir($handle);
					sort($list);
					foreach ($list as $item) {
						echo '<a href="' . $item . '" target="_blank" class="list-group-item">' . $item . '</a>';
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
</body>
</html>