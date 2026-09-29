<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if(isset($_POST['save-highlight']))
{
$id = null;
$start = null;
$length = null;
if(isset($_POST['id']))
{
$id = intval(sanitize_text_field($_POST['id']));
}
if(isset($_POST['start']))
{
$start = sanitize_text_field($_POST['start']);
}
if(isset($_POST['length']))
{
$length = sanitize_text_field($_POST['length']);
}
if($id)
{
$highlight = "";
if(!is_null($start))
{
$highlight = $start . ',' . $length;
}
$wpdb->query("UPDATE `". $trustindex_pm_google->get_tablename('reviews') ."` SET highlight = '$highlight' WHERE id = '$id'");
}
exit;
}
/* Replied flag saving:
- Google: comes after source connect
- Facebook: we saved internal
- other: dont save anything & only show "Reply with ChatGPT" button
*/
if(isset($_POST['save-reply']))
{
$id = null;
$reply = null;
if(isset($_POST['id']))
{
$id = intval(sanitize_text_field($_POST['id']));
}
$reply = wp_kses_post(stripslashes($_POST['save-reply']));
if($id && $reply)
{
$wpdb->query("UPDATE `". $trustindex_pm_google->get_tablename('reviews') ."` SET reply = '". str_replace("'", "\'", $reply) ."' WHERE id = '$id'");
}
exit;
}
if(isset($_POST['save-reply-generated']))
{
update_option($trustindex_pm_google->get_option_name('reply-generated'), 1, false);
exit;
}
if(isset($_POST['review_download_request']))
{
delete_option($trustindex_pm_google->get_option_name('review-download-token'));
update_option($trustindex_pm_google->get_option_name('review-download-inprogress'), sanitize_text_field($_POST['review_download_request']), false);
update_option($trustindex_pm_google->get_option_name('review-manual-download'), intval($_POST['manual_download']), false);
if(isset($_POST['review_download_request_id']))
{
update_option($trustindex_pm_google->get_option_name('review-download-request-id'), sanitize_text_field($_POST['review_download_request_id']), false);
}
update_option($trustindex_pm_google->get_option_name('review-download-modal'), 0, false);
exit;
}
if(isset($_POST['review_download_timestamp']))
{
$page_details = isset($_POST['page_details']) ? json_decode(stripcslashes($_POST['page_details']), true) : null;
if(isset($page_details['reviews']) && is_array($page_details['reviews']))
{
$trustindex_pm_google->save_reviews($page_details['reviews']);
}
update_option($trustindex_pm_google->get_option_name('download-timestamp'), intval($_POST['review_download_timestamp']), false);
exit;
}
$reviews = [];
if($trustindex_pm_google->is_noreg_linked())
{
$reviews = $wpdb->get_results('SELECT * FROM `'. $trustindex_pm_google->get_tablename('reviews') .'` ORDER BY date DESC');
}
$is_review_download_in_progress = $trustindex_pm_google->is_review_download_in_progress();
$review_download_request_id = get_option($trustindex_pm_google->get_option_name('review-download-request-id'));
function trustindex_plugin_write_rating_stars($score)
{
global $trustindex_pm_google;
if($trustindex_pm_google->is_ten_scale_rating_platform())
{
return '<div class="ti-rating-box">'. $trustindex_pm_google->formatTenRating($score) .'</div>';
}
$text = "";
$link = "https://cdn.trustindex.io/assets/platform/".ucfirst("google")."/star/";
if(!is_numeric($score))
{
return $text;
}
for ($si = 1; $si <= $score; $si++)
{
$text .= '<img src="'. $link .'f.svg" class="ti-star" />';
}
$fractional = $score - floor($score);
if( 0.25 <= $fractional )
{
if ( $fractional < 0.75 )
{
$text .= '<img src="'. $link .'h.svg" class="ti-star" />';
}
else
{
$text .= '<img src="'. $link .'f.svg" class="ti-star" />';
}
$si++;
}
for (; $si <= 5; $si++)
{
$text .= '<img src="'. $link .'e.svg" class="ti-star" />';
}
return $text;
}
wp_enqueue_style('trustindex-widget-css', 'https://cdn.trustindex.io/assets/widget-presetted-css/4-light-background.css');
wp_enqueue_script('trustindex-review-js', 'https://cdn.trustindex.io/assets/js/trustindex-review.js', [], false, true);
wp_add_inline_script('trustindex-review-js', '
jQuery(".ti-review-content").TI_shorten({
"showLines": 2,
"lessText": "'. TrustindexPlugin_google::___("Show less") .'",
"moreText": "'. TrustindexPlugin_google::___("Show more") .'",
});
jQuery(".ti-review-content").TI_format();
');
$download_timestamp = get_option($trustindex_pm_google->get_option_name('download-timestamp'), time() - 1);
$page_details = get_option($trustindex_pm_google->get_option_name('page-details'));
?>
<?php if(!$trustindex_pm_google->is_noreg_linked()): ?>
<div class="ti-notice notice-warning" style="margin-left: 0">
<p><?php echo TrustindexPlugin_google::___("Connect your %s platform to download reviews.", ["Google"]); ?></p>
</div>
<?php else: ?>
<?php if($trustindex_pm_google->is_trustindex_connected() && in_array($selected_tab, [ 'setup_no_reg', 'my_reviews' ])): ?>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexPlugin_google::___("You have connected your Trustindex account, so you can find premium functionality under the \"%s\" tab. You no longer need this tab unless you choose the limited but forever free mode.", ["Trustindex admin"]); ?>
</p>
</div>
<?php endif; ?>
<div class="ti-box">
<div class="ti-box-header"><?php echo TrustindexPlugin_google::___("My Reviews"); ?></div>
<?php if(!$is_review_download_in_progress): ?>
<div class="tablenav top" style="margin-bottom: 26px">
<div class="alignleft actions">
<?php if($download_timestamp < time()): ?>
<a href="#" class="btn-text btn-refresh btn-download-reviews" style="margin-left: 0" data-delay=10><?php echo TrustindexPlugin_google::___("Download new reviews") ;?></a>
<?php else: ?>
<a href="#" class="btn-text btn-disabled" style="margin-left: 0; pointer-events: none"> <?php echo TrustindexPlugin_google::___("Download new reviews"); ?></a>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p style="margin: 6px 0">
<?php echo TrustindexPlugin_google::___('You have to wait to be able to update.'); ?>
 <a href="https://www.trustindex.io/frequently-asked-questions/#my-reviews-aren-t-updating"><?php echo TrustindexPlugin_google::___('Why?'); ?></a>
</p>
</div>
<?php endif; ?>
</div>
</div>
<div class="ti-notice notice-info" style="margin: 15px 0; display: none" id="ti-connect-info">
<p><?php echo TrustindexPlugin_google::___("A popup window should be appear! Please, go to there and continue the steps! (If there is no popup window, you can check the the browser's popup blocker)"); ?></p>
</div>
<?php $page_details = get_option( $trustindex_pm_google->get_option_name('page-details') ); ?>
<input type="hidden" id="ti-noreg-page-id" value="<?php echo esc_attr($page_details['id']); ?>" />
<input type="hidden" id="ti-noreg-webhook-url" value="<?php echo $trustindex_pm_google->get_webhook_url(); ?>" />
<input type="hidden" id="ti-noreg-email" value="<?php echo get_option('admin_email'); ?>" />
<input type="hidden" id="ti-noreg-version" value="10.5" />
<?php if(isset($page_details['access_token'])): ?>
<input type="hidden" id="ti-noreg-access-token" value="<?php echo esc_attr($page_details['access_token']); ?>" />
<?php endif; ?>
<?php
$review_download_token = get_option($trustindex_pm_google->get_option_name('review-download-token'));
if(!$review_download_token)
{
$review_download_token = wp_create_nonce('ti-noreg-connect-token');
update_option($trustindex_pm_google->get_option_name('review-download-token'), $review_download_token, false);
}
?>
<input type="hidden" id="ti-noreg-connect-token" name="ti-noreg-connect-token" value="<?php echo $review_download_token; ?>" />
<?php endif; ?>
<?php if(!$trustindex_pm_google->is_trustindex_connected() && $download_timestamp < time()): ?>
<div class="ti-notice notice-error" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexPlugin_google::___("Do not waste your time by updating manually!"); ?> <?php echo TrustindexPlugin_google::___("We will solve it for you, for less than the price of a slice of pepperoni pizza per month!"); ?> <a href="https://www.trustindex.io/ti-redirect.php?a=sys&c=wp-google-pizza"><?php echo TrustindexPlugin_google::___("Subscribe!"); ?> »</a>
</p>
</div>
<?php endif; ?>
<?php if($is_review_download_in_progress === 'error'): ?>
<div class="ti-notice notice-error" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexPlugin_google::___('While downloading the reviews, we noticed that your connected page is not found.<br />If it really exists, please contact us to resolve the issue or try connect it again.'); ?><br />
</p>
</div>
<?php elseif($is_review_download_in_progress): ?>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexPlugin_google::___('Your reviews are downloading in the background.'); ?>
<?php if(!in_array('google', [ 'facebook', 'google' ])): ?>
<?php echo TrustindexPlugin_google::___('This can take up to a few hours depending on the load and platform.'); ?>
<?php endif; ?>
<?php if(!count($reviews)): ?>
<br />
<?php echo TrustindexPlugin_google::___('In the meantime, you can setup your widget with a few example reviews.'); ?>
<?php endif; ?>
<?php if($trustindex_pm_google->is_review_manual_download()): ?>
<br />
<a href="#" id="review-manual-download" class="button button-primary ti-tooltip" style="margin-top: 10px">
<?php echo TrustindexPlugin_google::___("Manual download") ;?>
<span class="ti-tooltip-message">
<?php echo TrustindexPlugin_google::___('Your reviews are downloading in the background.'); ?>
<?php if(!in_array('google', [ 'facebook', 'google' ])): ?>
<?php echo TrustindexPlugin_google::___('This can take up to a few hours depending on the load and platform.'); ?>
<?php endif; ?>
</span>
</a>
<?php endif; ?>
</p>
</div>
<?php endif; ?>
<?php if(!count($reviews)): ?>
<?php if(!$is_review_download_in_progress): ?>
<div class="ti-notice notice-warning" style="margin-left: 0">
<p><?php echo TrustindexPlugin_google::___("You had no reviews at the time of last review downloading."); ?></p>
</div>
<?php endif; ?>
<?php else: ?>
<table class="wp-list-table widefat fixed striped table-view-list ti-my-reviews ti-widget">
<thead>
<tr>
<th class="text-center"><?php echo TrustindexPlugin_google::___("Reviewer"); ?></th>
<th class="text-center" style="width: 90px;"><?php echo TrustindexPlugin_google::___("Rating"); ?></th>
<th class="text-center"><?php echo TrustindexPlugin_google::___("Date"); ?></th>
<th style="width: 50%"><?php echo TrustindexPlugin_google::___("Text"); ?></th>
<!-- <th style="width: 150px"></th> -->
</tr>
</thead>
<tbody>
<?php foreach ($reviews as $review): ?>
<?php $review_text = $trustindex_pm_google->getReviewHtml($review); ?>
<tr data-id="<?php echo esc_attr($review->id); ?>">
<td class="text-center">
<img src="<?php echo esc_url($review->user_photo); ?>" class="ti-user-avatar" /><br />
<?php echo esc_html($review->user); ?>
</td>
<td class="text-center source-<?php echo ucfirst("google") ?>"><?php echo trustindex_plugin_write_rating_stars($review->rating); ?></td>
<td class="text-center"><?php echo esc_html($review->date); ?></td>
<td>
<div class="ti-review-content"><?php echo $review_text; ?></div>
<?php
$state = 'reply';
if($review->reply)
{
$state = 'replied';
}
else if(!in_array('google', [ 'facebook', 'google' ]))
{
$state = 'copy-reply';
}
$hide_reply_button = false;
if(in_array('google', [ 'facebook', 'google' ]))
{
$hide_reply_button = $is_review_download_in_progress || get_option($trustindex_pm_google->get_option_name('review-download-modal'), 1);
}
?>
<?php if(!$hide_reply_button): ?>
<?php if($review->reply): ?>
<a href="#" class="btn-text btn-default btn-default-disabled btn-sm btn-show-ai-reply"><?php echo TrustindexPlugin_google::___('Reply'); ?></a>
<?php else: ?>
<a href="#" class="btn-text btn-sm btn-show-ai-reply" data-edit-reply-text="<?php echo TrustindexPlugin_google::___('Reply'); ?>"><?php echo TrustindexPlugin_google::___('Reply with ChatGPT'); ?></a>
<?php endif; ?>
<?php endif; ?>
<?php if($review->text): ?>
<a href="<?php echo esc_attr($review->id); ?>" class="btn-text btn-sm btn-default btn-show-highlight<?php if(isset($review->highlight) && $review->highlight): ?> has-highlight<?php endif; ?>"><?php echo TrustindexPlugin_google::___("Highlight text") ;?></a>
<?php endif; ?>
<?php if(!$hide_reply_button): ?>
<div class="ti-button-dropdown ti-reply-box<?php if($state == 'replied'): ?> active<?php endif; ?>" data-state="<?php echo $state; ?>" data-original-state="<?php echo $state; ?>">
<span class="ti-button-dropdown-arrow" data-button=".btn-show-ai-reply"></span>
<?php if($state != 'copy-reply'): ?>
<div class="ti-reply-box-state state-reply">
<div class="ti-button-dropdown-title">
<strong><?php echo TrustindexPlugin_google::___('ChatGPT generated reply'); ?></strong>
<span><?php echo TrustindexPlugin_google::___('you can modify before upload'); ?>
</div>
<textarea id="ti-ai-reply-<?php echo esc_attr($review->id); ?>" rows="1"></textarea>
<a href="<?php echo esc_attr($review->id); ?>" class="btn-text btn-sm btn-post-reply"><?php echo TrustindexPlugin_google::___('Upload reply to %s', [ 'Google' ]); ?></a>
<a href="#" class="btn-text btn-no-background btn-sm btn-hide-ai-reply"><?php echo TrustindexPlugin_google::___('Cancel'); ?></a>
</div>
<div class="ti-reply-box-state state-replied">
<div class="ti-button-dropdown-title">
<strong><?php echo TrustindexPlugin_google::___('Reply by %s', [ $page_details['name'] ]); ?></strong>
</div>
<div class="ti-alert d-none"><?php echo TrustindexPlugin_google::___('Reply successfully uploaded.'); ?></div>
<p><?php echo esc_html($review->reply); ?></p>
<?php if('google' != 'facebook'): ?>
<a href="<?php echo esc_attr($review->id); ?>" class="btn-text btn-white btn-sm btn-show-edit-reply"><?php echo TrustindexPlugin_google::___('Edit reply'); ?></a>
<?php endif; ?>
</div>
<div class="ti-reply-box-state state-edit-reply">
<div class="ti-button-dropdown-title">
<strong><?php echo TrustindexPlugin_google::___('Edit reply'); ?></strong>
<span><?php echo TrustindexPlugin_google::___('change your previous reply'); ?>
</div>
<textarea rows="1"><?php echo esc_html($review->reply); ?></textarea>
<a href="<?php echo esc_attr($review->id); ?>" class="btn-text btn-sm btn-post-reply"><?php echo TrustindexPlugin_google::___('Upload reply to %s', [ 'Google' ]); ?></a>
<a href="#" class="btn-text btn-no-background btn-sm btn-hide-edit-reply"><?php echo TrustindexPlugin_google::___('Cancel'); ?></a>
</div>
<?php endif; ?>
<div class="ti-reply-box-state state-copy-reply">
<div class="ti-button-dropdown-title">
<strong><?php echo TrustindexPlugin_google::___('Copy the reply'); ?></strong>
</div>
<div class="ti-alert ti-alert-warning d-none">
<?php echo TrustindexPlugin_google::___('We could not connect your account with the review.'); ?>
<a href="#" class="btn-try-reply-again"><?php echo TrustindexPlugin_google::___('Try again'); ?></a>
</div>
<textarea id="ti-copy-ai-reply-<?php echo esc_attr($review->id); ?>" rows="1"></textarea>
<a href="#ti-copy-ai-reply-<?php echo esc_attr($review->id); ?>" class="btn-text btn-sm btn-copy2clipboard ti-tooltip toggle-tooltip">
<?php echo TrustindexPlugin_google::___("Copy to clipboard") ;?>
<span class="ti-tooltip-message">
<span style="color: #00ff00; margin-right: 2px">✓</span>
<?php echo TrustindexPlugin_google::___("Copied"); ?>
</span>
</a>
<a href="#" class="btn-text btn-no-background btn-sm btn-hide-ai-reply"><?php echo TrustindexPlugin_google::___('Cancel'); ?></a>
</div>
</div>
<script type="application/ld+json"><?php echo json_encode([
'source' => [
'page_id' => $page_details['id'],
'name' => $page_details['name'],
'reviews' => [
'count' => $page_details['rating_number'],
'score' => $page_details['rating_score'],
],
'access_token' => isset($page_details['access_token']) ? $page_details['access_token'] : null
],
'review' => [
'id' => $review->reviewId,
'reviewer' => [
'name' => $review->user,
'avatar_url' => $review->user_photo
],
'rating' => $review->rating,
'text' => $review->text,
'created_at' => $review->date
]
]); ?></script>
<?php endif; ?>
<?php if($review->text): ?>
<div class="ti-button-dropdown ti-highlight-box">
<span class="ti-button-dropdown-arrow" data-button=".btn-show-highlight"></span>
<div class="ti-button-dropdown-title">
<strong><?php echo TrustindexPlugin_google::___('Highlight text'); ?></strong>
<span><?php echo TrustindexPlugin_google::___('just select the text you want to highlight'); ?>
</div>
<div class="ti-highlight-content">
<div class='raw-content'><?php echo $review_text; ?></div>
<div class='selection-content'><?php echo preg_replace('/<mark class="ti-highlight">/', '', $review_text); ?></div>
</div>
<a href="<?php echo esc_attr($review->id); ?>" class="btn-text btn-sm btn-save-highlight"><?php echo TrustindexPlugin_google::___('Save'); ?></a>
<a href="#" class="btn-text btn-no-background btn-sm btn-hide-highlight"><?php echo TrustindexPlugin_google::___('Cancel'); ?></a>
<?php if($review->highlight): ?>
<a href="<?php echo esc_attr($review->id); ?>" class="btn-text btn-danger btn-sm btn-remove-highlight btn-pull-right"><?php echo TrustindexPlugin_google::___('Remove highlight'); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
<?php if($reviews && get_option($trustindex_pm_google->get_option_name('review-download-modal'), 1) && in_array('google', [ 'facebook', 'google' ])): ?>
<div class="ti-modal ti-rateus-modal" style="display: block">
<div class="ti-modal-dialog">
<div class="ti-modal-content">
<span class="ti-close-icon btn-modal-close"></span>
<div class="ti-modal-body">
<div class="ti-rateus-title"><?php echo TrustindexPlugin_google::___('New feature: %s', [ TrustindexPlugin_google::___('Reply with ChatGPT') ]); ?></div>
<p>
<?php echo TrustindexPlugin_google::___('In order to use this feature, your reviews need to be updated.'); ?><br />
<?php echo TrustindexPlugin_google::___('This could take a little while, so please refresh the page after 3 minutes!'); ?>
</p>
</div>
<div class="ti-modal-footer">
<a href="#" class="btn-text btn-default btn-modal-close"><?php echo TrustindexPlugin_google::___('Cancel') ;?></a>
<a href="#" class="btn-text btn-refresh btn-download-reviews"><?php echo TrustindexPlugin_google::___('Update') ;?></a>
</div>
</div>
</div>
</div>
<?php endif; ?>
<?php if(class_exists('Woocommerce')): ?>
<div class="ti-box">
<div class="ti-box-header"><?php echo TrustindexPlugin_google::___('Collect reviews automatically for your WooCommerce shop'); ?></div>
<?php if(!class_exists('TrustindexCollectorPlugin')): ?>
<p><?php echo TrustindexPlugin_google::___("Download our new <a href='%s' target='_blank'>%s</a> plugin and get features for free!", [ 'https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/', TrustindexPlugin_google::___('Customer Reviews Collector for WooCommerce') ]); ?></p>
<?php endif; ?>
<ul class="ti-check" style="margin-bottom: 20px">
<li><?php echo TrustindexPlugin_google::___('Send unlimited review invitations for free'); ?></li>
<li><?php echo TrustindexPlugin_google::___('E-mail templates are fully customizable'); ?></li>
<li><?php echo TrustindexPlugin_google::___('Collect reviews on 100+ review platforms (Google, Facebook, Yelp, etc.)'); ?></li>
</ul>
<?php if(class_exists('TrustindexCollectorPlugin')): ?>
<a href="?page=customer-reviews-collector-for-woocommerce%2Fadmin.php&tab=settings" class="btn-text">
<?php echo TrustindexPlugin_google::___("Collect reviews automatically"); ?>
</a>
<?php else: ?>
<a href="https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/" target="_blank" class="btn-text">
<?php echo TrustindexPlugin_google::___("Download plugin"); ?>
</a>
<?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>