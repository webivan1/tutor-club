<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <table align="center" width="600px" border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td>
                    <?php echo $__env->yieldContent('content'); ?>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
