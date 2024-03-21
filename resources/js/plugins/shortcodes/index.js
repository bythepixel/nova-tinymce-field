import {
  buildShortcodeStringFromData,
  createTitlePanel,
  resetTabData
} from './utils';

export default class Shortcodes {

  constructor(shortcodes) {
    this.shortcodes = shortcodes;
  }

  /**
   * Builds a set of tabs to include in the dialog
   *
   * @returns { Object }
   */
  buildTabs() {
    return this.shortcodes.map((shortcode) => {

      // Create whatever inputs are required by the shortcode. The 'name' property
      // uses the shortcode.slug to basically namespace an input. This is
      // largely necessary because all inputs in a TinyMCE dialog are stored
      // in a flat object returned by the dialog's getData() method.
      const inputPanels = shortcode.panels.map((panel) => {
        return {
          type: 'input',
          name: `${shortcode.slug}.${panel.name}`,
          label: panel.label
        }
      })

      const titlePanel = createTitlePanel(shortcode.tabTitle);

      const tabItems = [{
        type: 'panel',
        items: [
          ...titlePanel,
          ...inputPanels
        ]
      }]

      return {
        name: shortcode.slug,
        title: shortcode.tabTitle,
        items: tabItems
      }
    })
  }

  /**
   * Fires when the toolbar button is clicked. A TinyMCE Editor instance is passed
   * to the callback
   * @param { Editor } editor - A TinyMCE Editor instance
   */
  openDialog(editor) {
    const tabs = this.buildTabs();
    const buttons = [
      {
        type: 'cancel',
        text: 'Cancel'
      },
      {
        type: 'submit',
        text: 'Insert',
        primary: true
      }
    ];

    editor.windowManager.open({
      title: 'Insert Shortcode',
      body: {
        type: 'tabpanel',
        tabs
      },
      buttons,

      // To avoid stragglers in the dialog's state store when it's
      // time to insert a shortcode, we reset the data whenever the
      // tab changes
      onTabChange: this.handleTabChangeEvent,

      onSubmit: this.handleSubmissionEvent
    });
  }

  /**
   * Fires when a tab is changed. The callback receives a Dialog instance
   *
   * @param { Dialog } dialogApi - A TinyMCE Dialog instance
   * @returns { void }
   */
  handleTabChangeEvent(dialogApi) {
    resetTabData(dialogApi);
  }

  /**
   * Dialog submission handler. Builds a shortcode snippet from the input data
   * and inserts the snippet into the WYSIWYG content
   *
   * @param { Dialog } dialogApi - A TinyMCE Dialog instance
   * @returns { void }
   */
  handleSubmissionEvent(dialogApi) {
    const inputData = dialogApi.getData();
    const shortcodeString = buildShortcodeStringFromData(inputData);
    editor.insertContent(shortcodeString);

    dialogApi.close();
  }

  /**
   * TinyMCE's custom plugin registration can be handled a few ways, one of which
   * is to pass a closure as a second argument to the PluginManager's add() method.
   */
  init() {
    return (editor) => {
      const toolbarButton = {
        text: 'Shortcodes',
        onAction: () => this.openDialog(editor)
      }

      // Register the button with the editor. Once registered, the button can be
      // added to the 'toolbar' array in the published config
      editor.ui.registry.addButton('shortcodes', toolbarButton);
    }
  }
}
