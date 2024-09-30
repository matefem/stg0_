const {__} = wp.i18n;
const {Fragment} = wp.element;
const {registerBlockType} = wp.blocks;
const {PanelBody, PanelRow, ToggleControl} = wp.components;
const {withState} = wp.compose;

class TheiaSmartThumbnailsBlock extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showPreview: wpCookies ? wpCookies.get('theiaSmartThumbnails_showPreview') === 'true' : false,
            position: [0.5, 0.5]
        };

        this.imageRef = React.createRef();
        this.previewRef = React.createRef();
        this.picker = null;
        this.positionHasBeenFetched = false;

        // Create dummy input and save logic.
        this.input = jQuery('<input>');
        this.input.on('change', () => {
            const position = this.input.val();
            jQuery.ajax({
                url: window.tstData.restApi + '/focus-point/' + this.props.id,
                method: 'POST',
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                },
                data: {
                    focus_point: position
                }
            }).done(() => {
                this.setState({
                    position: JSON.parse(position)
                });
            });
        });
    }

    render() {
        if (wpCookies) {
            wpCookies.set('theiaSmartThumbnails_showPreview', this.state.showPreview ? 'true' : 'false');
        }

        if (!this.props.id) {
            return (
                <div>No image yet.</div>
            );
        }

        return (
            <div className="theiaSmartThumbnails_mediaUpload _gutenberg">
                <div ref={this.imageRef} className="_picker">
                    <img src={this.props.url} alt='' />
                </div>

                <p style={{'font-style': 'italic', 'margin-bottom': '1.5em'}}>
                    Click on the point of interest - the area you want included in the thumbnails. Drag your cursor to experiment.
                </p>

                <ToggleControl
                    label=" Show Preview"
                    checked={this.state.showPreview}
                    onChange={() => this.setState((state) => ({showPreview: !state.showPreview}))}
                />

                <div ref={this.previewRef}
                     className=" _preview"
                     style={{display: this.state.showPreview ? '' : 'none'}} />
            </div>
        );
    }

    componentDidMount() {
        this.createPicker();
    }

    componentDidUpdate() {
        this.createPicker();
    }

    componentDidUnmount() {
        if (this.picker) {
            this.picker.destroy();
        }
    }

    getSnapshotBeforeUpdate() {
        if (this.picker) {
            this.picker.destroy();
        }
    }

    createPicker() {
        // Create non-React picker.
        this.picker = new tst.createPicker({
            attachmentId: '',
            image: this.imageRef.current,
            input: this.input,
            preview: this.previewRef.current,
            sizes: window.tstData.sizes,
            position: {
                x: this.state.position[0],
                y: this.state.position[1]
            },
            gutenberg: true
        });

        // If this is the first time we're showing this picker, we need to fetch the image position via the REST API.
        if (!this.positionHasBeenFetched) {
            if (!this.props.id) {
                return;
            }

            this.positionHasBeenFetched = true;
            this.picker.isLoading = true;
            this.picker.imageOverlay.addClass('_loader');

            jQuery.ajax({
                url: window.tstData.restApi + '/focus-point/' + this.props.id,
                method: 'GET',
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                }
            }).done((response) => {
                this.setState({
                    position: response
                })
            });
        }
    }
}

function filterImageBlock(settings, name) {
    // Only consider the Image block.
    if (name !== 'core/image') {
        return settings;
    }

    const {InspectorControls} = wp.editor;
    const originalEdit = settings.edit;

    // Overwrite the Edit function so we can add our own blocks in the right-hand sidebar.
    settings.edit = (props) => {
        return [
            wp.element.createElement(originalEdit, props),
            (
                <Fragment>
                    <InspectorControls>
                        <PanelBody
                            title=" Theia Smart Thumbnails"
                            initialOpen={true}
                        >
                            <PanelRow>
                                <TheiaSmartThumbnailsBlock url={props.attributes.url}
                                                           id={props.attributes.id} />
                            </PanelRow>
                        </PanelBody>
                    </InspectorControls>
                </Fragment>
            ),
        ];
    };

    return lodash.assign({}, settings, {
        supports: lodash.assign({}, settings.supports, {
            className: true
        }),
    });
}

// Add filter when registering block types.
wp.hooks.addFilter(
    'blocks.registerBlockType',
    'my-plugin/class-names/list-block',
    filterImageBlock
);
