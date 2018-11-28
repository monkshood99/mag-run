<?php
namespace ACP\Admin;

use AC\Ajax;

class Feedback {

	public function register() {
		add_action( 'ac/settings/after_menu', array( $this, 'feedback_sidebox' ) );

		$this->get_ajax_handler()->register();
	}

	public function feedback_sidebox() {
		wp_enqueue_style( 'acp-feedback', ACP()->get_url() . 'assets/css/feedback.css', array(), ACP()->get_version() );
		wp_enqueue_script( 'acp-feedback', ACP()->get_url() . 'assets/js/feedback.js', array(), ACP()->get_version() );

		?>
		<div class="ac-modal -feedback" id="ac-modal-feedback">
			<div class="ac-modal__dialog -feedback">
				<form method="post" id="frm-ac-feedback">
					<div class="ac-modal__dialog__header">
						<?php _e( 'Leave your feedback', 'codepress-admin-columns' ); ?>
						<button class="ac-modal__dialog__close" data-dismiss="modal">
							<span class="dashicons dashicons-no"></span>
						</button>
					</div>
					<div class="ac-modal__dialog__content">
						<input name="_ajax_nonce" value="<?php echo $this->get_ajax_handler()->get_param( '_ajax_nonce' ); ?>" type="hidden" readonly>

						<div class="field-group">
							<label for="frm_ac_fb_email"><?php _e( 'Your Email', 'codepress-admin-columns' ); ?></label>
							<input type="email" name="name" id="frm_ac_fb_email" required value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>" autocomplete="off">
						</div>
						<div class="field-group">
							<label for="frm_ac_fb_feedback"><?php _e( 'Feedback', 'codepress-admin-columns' ); ?></label>
							<textarea name="feedback" id="frm_ac_fb_feedback" rows="6" autocomplete="off"></textarea>
						</div>
						<div class="ac-feedback__error"></div>
					</div>
					<div class="ac-modal__dialog__footer">
						<button type="submit" class="button-primary" value="send" name="frm_ac_fb_submit"><?php _e( 'Send feedback', 'codepress-admin-columns' ); ?></button>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * @return Ajax\Handler
	 */
	protected function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler->set_action( 'acp-send-feedback' )
		        ->set_callback( array( $this, 'ajax_send_feedback' ) );

		return $handler;
	}

	public function ajax_send_feedback() {
		$this->get_ajax_handler()->verify_request();

		$email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );

		if ( ! is_email( $email ) ) {
			wp_send_json_error( __( 'Please insert a valid email so we can reply to your feedback.', 'codepress-admin-columns' ) );
		}

		$feedback = filter_input( INPUT_POST, 'feedback', FILTER_SANITIZE_STRING );

		if ( empty( $feedback ) ) {
			wp_send_json_error( __( 'Your feedback form is empty.', 'codepress-admin-columns' ) );
		}

		$headers = array(
			sprintf( 'From: <%s>', trim( $email ) ),
			'Content-Type: text/html',
		);

		wp_mail(
			acp_support_email(),
			sprintf( 'Beta Feedback on Admin Columns Pro %s', ACP()->get_version() ),
			nl2br( $feedback ),
			$headers
		);

		wp_send_json_success( __( 'Thank you very much for your feedback!' ) );
	}

}