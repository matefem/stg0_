/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { PanelBody, PanelRow, ToggleControl } = wp.components;
const { withState } = wp.compose;

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
                beforeSend: xhr => {
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
            return React.createElement(
                'div',
                null,
                'No image yet.'
            );
        }

        return React.createElement(
            'div',
            { className: 'theiaSmartThumbnails_mediaUpload _gutenberg' },
            React.createElement(
                'div',
                { ref: this.imageRef, className: '_picker' },
                React.createElement('img', { src: this.props.url, alt: '' })
            ),
            React.createElement(
                'p',
                { style: { 'font-style': 'italic', 'margin-bottom': '1.5em' } },
                'Click on the point of interest - the area you want included in the thumbnails. Drag your cursor to experiment.'
            ),
            React.createElement(ToggleControl, {
                label: ' Show Preview',
                checked: this.state.showPreview,
                onChange: () => this.setState(state => ({ showPreview: !state.showPreview }))
            }),
            React.createElement('div', { ref: this.previewRef,
                className: ' _preview',
                style: { display: this.state.showPreview ? '' : 'none' } })
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
                beforeSend: xhr => {
                    xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                }
            }).done(response => {
                this.setState({
                    position: response
                });
            });
        }
    }
}

function filterImageBlock(settings, name) {
    // Only consider the Image block.
    if (name !== 'core/image') {
        return settings;
    }

    const { InspectorControls } = wp.editor;
    const originalEdit = settings.edit;

    // Overwrite the Edit function so we can add our own blocks in the right-hand sidebar.
    settings.edit = props => {
        return [wp.element.createElement(originalEdit, props), React.createElement(
            Fragment,
            null,
            React.createElement(
                InspectorControls,
                null,
                React.createElement(
                    PanelBody,
                    {
                        title: ' Theia Smart Thumbnails',
                        initialOpen: true
                    },
                    React.createElement(
                        PanelRow,
                        null,
                        React.createElement(TheiaSmartThumbnailsBlock, { url: props.attributes.url,
                            id: props.attributes.id })
                    )
                )
            )
        )];
    };

    return lodash.assign({}, settings, {
        supports: lodash.assign({}, settings.supports, {
            className: true
        })
    });
}

// Add filter when registering block types.
wp.hooks.addFilter('blocks.registerBlockType', 'my-plugin/class-names/list-block', filterImageBlock);

/***/ })
/******/ ]);