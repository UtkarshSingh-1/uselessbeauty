<?php
/**
 * Goals
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/goals.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($advanced) || empty($style)) {
    return;
}
$uwpmc_goals = array_filter($advanced['goals']['list'] ?? []);
if (empty($uwpmc_goals)) {
    return;
}
?>
<div class="uwpmc-goals"
     style="<?php echo(!empty($advanced['goals']['enable']) ? 'display: block;' : 'display: none;'); ?>">
    <?php foreach ($uwpmc_goals as $uwpmc_key => $uwpmc_goal) { ?>
        <div class="uwpmc-goal uwpmc-<?php echo esc_attr(str_replace('_', '-', $uwpmc_key)); ?>"
             style="border-bottom: 0.5px solid #e6e6e6; line-height: 0; text-align: center; <?php echo(empty($uwpmc_goal) ? 'display: none;' : 'display: block;'); ?>
             <?php echo !empty($style['goals']) ? esc_attr($style['goals']) : ''; ?>">
            <span class="uwpmc-goal-text"><?php echo wp_kses_post($uwpmc_goal['message'] ?? ''); ?></span>
            <input class="uwpmc-goal-range" type="range" min="0"
                   max="<?php echo !empty($uwpmc_goal['target']) ? esc_attr($uwpmc_goal['target']) : ''; ?>"
                   style="<?php echo !empty($style['goals']) ? esc_attr($style['goals']) : ''; ?>"
                   value="<?php echo !empty($uwpmc_goal['current']) ? esc_attr($uwpmc_goal['current']) : ''; ?>"
                   disabled/>
        </div>
    <?php } ?>
</div>