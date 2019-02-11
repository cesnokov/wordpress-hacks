<?php

// Override the "Attachments Details" and "Attachments Details Two Column" Backbone micro templates
add_action( 'admin_footer-post.php', 'modified_attachments_details_template' );
add_action( 'admin_footer-upload.php', 'modified_attachments_details_two_column_template' );

function modified_attachments_details_template(){
?>
  <script type="text/html" id="tmpl-attachment-details-custom">
    <h2>
      <?php _e( 'Attachment Details' ); ?>
      <span class="settings-save-status">
        <span class="spinner"></span>
        <span class="saved"><?php esc_html_e('Saved.'); ?></span>
      </span>
    </h2>
    <div class="attachment-info">
      <div class="thumbnail thumbnail-{{ data.type }}">
        <# if ( data.uploading ) { #>
          <div class="media-progress-bar"><div></div></div>
        <# } else if ( 'image' === data.type && data.sizes ) { #>
          <img src="{{ data.size.url }}" draggable="false" alt="" />
        <# } else { #>
          <img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
        <# } #>
      </div>
      <div class="details">
        <div class="filename">{{ data.filename }}</div>
        <div class="uploaded">{{ data.dateFormatted }}</div>

        <div class="file-size">{{ data.filesizeHumanReadable }}</div>
        <# if ( 'image' === data.type && ! data.uploading ) { #>
            <div class="file-id"><strong><?php _e( 'File ID:' ); ?></strong> {{ data.id }}</div>
          <# if ( data.width && data.height ) { #>
            <div class="dimensions">{{ data.width }} &times; {{ data.height }}</div>
          <# } #>

          <# if ( data.can.save && data.sizes ) { #>
            <a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank"><?php _e( 'Edit Image' ); ?></a>
          <# } #>
        <# } #>

        <# if ( data.fileLength ) { #>
          <div class="file-length"><?php _e( 'Length:' ); ?> {{ data.fileLength }}</div>
        <# } #>

        <# if ( ! data.uploading && data.can.remove ) { #>
          <?php if ( MEDIA_TRASH ): ?>
          <# if ( 'trash' === data.status ) { #>
            <button type="button" class="button-link untrash-attachment"><?php _e( 'Untrash' ); ?></button>
          <# } else { #>
            <button type="button" class="button-link trash-attachment"><?php _ex( 'Trash', 'verb' ); ?></button>
          <# } #>
          <?php else: ?>
            <button type="button" class="button-link delete-attachment"><?php _e( 'Delete Permanently' ); ?></button>
          <?php endif; ?>
        <# } #>

        <div class="compat-meta">
          <# if ( data.compat && data.compat.meta ) { #>
            {{{ data.compat.meta }}}
          <# } #>
        </div>
      </div>
    </div>

    <label class="setting" data-setting="url">
      <span class="name"><?php _e('URL'); ?></span>
      <input type="text" value="{{ data.url }}" readonly />
    </label>
    <# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
    <?php if ( post_type_supports( 'attachment', 'title' ) ) : ?>
    <label class="setting" data-setting="title">
      <span class="name"><?php _e('Title'); ?></span>
      <input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
    </label>
    <?php endif; ?>
    <# if ( 'audio' === data.type ) { #>
    <?php foreach ( array(
      'artist' => __( 'Artist' ),
      'album' => __( 'Album' ),
    ) as $key => $label ) : ?>
    <label class="setting" data-setting="<?php echo esc_attr( $key ) ?>">
      <span class="name"><?php echo $label ?></span>
      <input type="text" value="{{ data.<?php echo $key ?> || data.meta.<?php echo $key ?> || '' }}" />
    </label>
    <?php endforeach; ?>
    <# } #>
    <label class="setting" data-setting="caption">
      <span class="name"><?php _e('Caption'); ?></span>
      <textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
    </label>
    <# if ( 'image' === data.type ) { #>
      <label class="setting" data-setting="alt">
        <span class="name"><?php _e('Alt Text'); ?></span>
        <input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
      </label>
    <# } #>
    <label class="setting" data-setting="description">
      <span class="name"><?php _e('Description'); ?></span>
      <textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
    </label>
  </script>
  <script>
    jQuery(document).ready( function($) {
        if( typeof wp.media.view.Attachment.Details != 'undefined' ){
            wp.media.view.Attachment.Details.prototype.template = wp.media.template( 'attachment-details-custom' );
        }
    });
  </script>

<?php
}

function modified_attachments_details_two_column_template() {
?>
  <script type="text/html" id="tmpl-attachment-details-two-column-custom">
    <div class="attachment-media-view {{ data.orientation }}">
      <div class="thumbnail thumbnail-{{ data.type }}">
        <# if ( data.uploading ) { #>
          <div class="media-progress-bar"><div></div></div>
        <# } else if ( data.sizes && data.sizes.large ) { #>
          <img class="details-image" src="{{ data.sizes.large.url }}" draggable="false" alt="" />
        <# } else if ( data.sizes && data.sizes.full ) { #>
          <img class="details-image" src="{{ data.sizes.full.url }}" draggable="false" alt="" />
        <# } else if ( -1 === jQuery.inArray( data.type, [ 'audio', 'video' ] ) ) { #>
          <img class="details-image icon" src="{{ data.icon }}" draggable="false" alt="" />
        <# } #>

        <# if ( 'audio' === data.type ) { #>
        <div class="wp-media-wrapper">
          <audio style="visibility: hidden" controls class="wp-audio-shortcode" width="100%" preload="none">
            <source type="{{ data.mime }}" src="{{ data.url }}"/>
          </audio>
        </div>
        <# } else if ( 'video' === data.type ) {
          var w_rule = '';
          if ( data.width ) {
            w_rule = 'width: ' + data.width + 'px;';
          } else if ( wp.media.view.settings.contentWidth ) {
            w_rule = 'width: ' + wp.media.view.settings.contentWidth + 'px;';
          }
        #>
        <div style="{{ w_rule }}" class="wp-media-wrapper wp-video">
          <video controls="controls" class="wp-video-shortcode" preload="metadata"
            <# if ( data.width ) { #>width="{{ data.width }}"<# } #>
            <# if ( data.height ) { #>height="{{ data.height }}"<# } #>
            <# if ( data.image && data.image.src !== data.icon ) { #>poster="{{ data.image.src }}"<# } #>>
            <source type="{{ data.mime }}" src="{{ data.url }}"/>
          </video>
        </div>
        <# } #>

        <div class="attachment-actions">
          <# if ( 'image' === data.type && ! data.uploading && data.sizes && data.can.save ) { #>
          <button type="button" class="button edit-attachment"><?php _e( 'Edit Image' ); ?></button>
          <# } else if ( 'pdf' === data.subtype && data.sizes ) { #>
          <?php _e( 'Document Preview' ); ?>
          <# } #>
        </div>
      </div>
    </div>
    <div class="attachment-info">
      <span class="settings-save-status">
        <span class="spinner"></span>
        <span class="saved"><?php esc_html_e('Saved.'); ?></span>
      </span>
      <div class="details">
        <div class="filename"><strong><?php _e( 'File name:' ); ?></strong> {{ data.filename }}</div>
        <div class="filename"><strong><?php _e( 'File type:' ); ?></strong> {{ data.mime }}</div>
        <div class="uploaded"><strong><?php _e( 'Uploaded on:' ); ?></strong> {{ data.dateFormatted }}</div> 
        <div class="file-size"><strong><?php _e( 'File size:' ); ?></strong> {{ data.filesizeHumanReadable }}</div>
        <div class="file-size"><strong><?php _e( 'File ID:' ); ?></strong> {{ data.id }}</div>

        <# if ( 'image' === data.type && ! data.uploading ) { #>
          <# if ( data.width && data.height ) { #>
            <div class="dimensions"><strong><?php _e( 'Dimensions:' ); ?></strong> {{ data.width }} &times; {{ data.height }}</div>
          <# } #>
        <# } #>

        <# if ( data.fileLength ) { #>
          <div class="file-length"><strong><?php _e( 'Length:' ); ?></strong> {{ data.fileLength }}</div>
        <# } #>

        <# if ( 'audio' === data.type && data.meta.bitrate ) { #>
          <div class="bitrate">
            <strong><?php _e( 'Bitrate:' ); ?></strong> {{ Math.round( data.meta.bitrate / 1000 ) }}kb/s
            <# if ( data.meta.bitrate_mode ) { #>
            {{ ' ' + data.meta.bitrate_mode.toUpperCase() }}
            <# } #>
          </div>
        <# } #>

        <div class="compat-meta">
          <# if ( data.compat && data.compat.meta ) { #>
            {{{ data.compat.meta }}}
          <# } #>
        </div>
      </div>

      <div class="settings">
        <label class="setting" data-setting="url">
          <span class="name"><?php _e('URL'); ?></span>
          <input type="text" value="{{ data.url }}" readonly />
        </label>
        <# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
        <?php if ( post_type_supports( 'attachment', 'title' ) ) : ?>
        <label class="setting" data-setting="title">
          <span class="name"><?php _e('Title'); ?></span>
          <input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
        </label>
        <?php endif; ?>
        <# if ( 'audio' === data.type ) { #>
        <?php foreach ( array(
          'artist' => __( 'Artist' ),
          'album' => __( 'Album' ),
        ) as $key => $label ) : ?>
        <label class="setting" data-setting="<?php echo esc_attr( $key ) ?>">
          <span class="name"><?php echo $label ?></span>
          <input type="text" value="{{ data.<?php echo $key ?> || data.meta.<?php echo $key ?> || '' }}" />
        </label>
        <?php endforeach; ?>
        <# } #>
        <label class="setting" data-setting="caption">
          <span class="name"><?php _e( 'Caption' ); ?></span>
          <textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
        </label>
        <# if ( 'image' === data.type ) { #>
          <label class="setting" data-setting="alt">
            <span class="name"><?php _e( 'Alt Text' ); ?></span>
            <input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
          </label>
        <# } #>
        <label class="setting" data-setting="description">
          <span class="name"><?php _e('Description'); ?></span>
          <textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
        </label>
        <div class="setting">
          <span class="name"><?php _e( 'Uploaded By' ); ?></span>
          <span class="value">{{ data.authorName }}</span>
        </div>
        <# if ( data.uploadedToTitle ) { #>
          <div class="setting">
            <span class="name"><?php _e( 'Uploaded To' ); ?></span>
            <# if ( data.uploadedToLink ) { #>
              <span class="value"><a href="{{ data.uploadedToLink }}">{{ data.uploadedToTitle }}</a></span>
            <# } else { #>
              <span class="value">{{ data.uploadedToTitle }}</span>
            <# } #>
          </div>
        <# } #>
        <div class="attachment-compat"></div>
      </div>

      <div class="actions">
        <a class="view-attachment" href="{{ data.link }}"><?php _e( 'View attachment page' ); ?></a>
        <# if ( data.can.save ) { #> |
          <a href="{{ data.editLink }}"><?php _e( 'Edit more details' ); ?></a>
        <# } #>
        <# if ( ! data.uploading && data.can.remove ) { #> |
          <?php if ( MEDIA_TRASH ): ?>
            <# if ( 'trash' === data.status ) { #>
              <button type="button" class="button-link untrash-attachment"><?php _e( 'Untrash' ); ?></button>
            <# } else { #>
              <button type="button" class="button-link trash-attachment"><?php _ex( 'Trash', 'verb' ); ?></button>
            <# } #>
          <?php else: ?>
            <button type="button" class="button-link delete-attachment"><?php _e( 'Delete Permanently' ); ?></button>
          <?php endif; ?>
        <# } #>
      </div>

    </div>
  </script>
  <script>
        jQuery(document).ready( function($) {
            if( typeof wp.media.view.Attachment.Details.TwoColumn != 'undefined' ){
                wp.media.view.Attachment.Details.TwoColumn.prototype.template = wp.template( 'attachment-details-two-column-custom' );
            }
        });
    </script>

<?php
}

function modified_gallery_details_template() {
?>
  <script type="text/html" id="tmpl-gallery-settings-custom">
    <h2><?php _e( 'Gallery Settings' ); ?></h2>

    <label class="setting">
      <span><?php _e('Link To'); ?></span>
      <select class="link-to"
        data-setting="link"
        <# if ( data.userSettings ) { #>
          data-user-setting="urlbutton"
        <# } #>>

        <option value="post" <# if ( ! wp.media.galleryDefaults.link || 'post' == wp.media.galleryDefaults.link ) {
          #>selected="selected"<# }
        #>>
          <?php esc_html_e( 'Attachment Page' ); ?>
        </option>
        <option value="file" <# if ( 'file' == wp.media.galleryDefaults.link ) { #>selected="selected"<# } #>>
          <?php esc_html_e( 'Media File' ); ?>
        </option>
        <option value="none" <# if ( 'none' == wp.media.galleryDefaults.link ) { #>selected="selected"<# } #>>
          <?php esc_html_e( 'None' ); ?>
        </option>
      </select>
    </label>

    <label class="setting">
      <span><?php _e('Columns'); ?></span>
      <select class="columns" name="columns"
        data-setting="columns">
        <?php for ( $i = 1; $i <= 9; $i++ ) : ?>
          <option value="<?php echo esc_attr( $i ); ?>" <#
            if ( <?php echo $i ?> == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
          #>>
            <?php echo esc_html( $i ); ?>
          </option>
        <?php endfor; ?>
      </select>
    </label>

    <label class="setting">
      <span><?php _e( 'Random Order' ); ?></span>
      <input type="checkbox" data-setting="_orderbyRandom" />
    </label>

    <label class="setting size">
      <span><?php _e( 'Size' ); ?></span>
      <select class="size" name="size"
        data-setting="size"
        <# if ( data.userSettings ) { #>
          data-user-setting="imgsize"
        <# } #>
        >
        <?php
        /** This filter is documented in wp-admin/includes/media.php */
        $size_names = apply_filters( 'image_size_names_choose', array(
          'thumbnail' => __( 'Thumbnail' ),
          'medium'    => __( 'Medium' ),
          'large'     => __( 'Large' ),
          'full'      => __( 'Full Size' ),
        ) );

        foreach ( $size_names as $size => $label ) : ?>
          <option value="<?php echo esc_attr( $size ); ?>">
            <?php echo esc_html( $label ); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
  </script>
  <script>
        jQuery(document).ready( function($) {
            if( typeof wp.media.view.Settings.Gallery != 'undefined' ){
                wp.media.view.Settings.Gallery.prototype.template = wp.media.template( 'gallery-settings-custom' );
            }
        });
    </script>

<?php
}

//EOF