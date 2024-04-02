/**
 * Builds an element for a panel title
 *
 * @param { String } shortcodeName
 * @returns { Object }
 */
function createTitlePanel(shortcodeName) {
  const htmlString =  `<h2 style="margin-bottom: 1.5rem">Create ${shortcodeName}</h2>`;

  return {
    type: 'htmlpanel',
    html: htmlString
  }
}

/**
 * Builds a string from an array of attributes to pass into the shortcode snippet
 *
 * Example output: 'foo="bar" bar="baz"'
 *
 * @param { Array<Object> } attributes - an array of attributes
 * @returns { String }
 */
function buildFormattedAttributes(attributes) {
  if (!attributes || attributes.length === 0) {
    return '';
  }

  return attributes.map((attribute) => {
    const { key, value } = attribute;

    return `${key}="${value}"`;
  }).join(" ");
}

/**
 * Produces a finalized shortcode string to be injected into the WYSIWYG content
 *
 * @param { Object } dialogData - a flat object
 * @returns { String }
 *
 */
function buildShortcodeStringFromData(dialogData) {
  let attributes = [];
  let shortcode;

  // Enumerate over our input data from the dialog, which is just key/value pairs
  for (const inputName in dialogData) {
    if (dialogData[inputName] === '') {
      continue;
    }

    const [ shortcodeSlug, attributeKey ] = inputName.split('.');

    if (!shortcode) {
      shortcode = shortcodeSlug;
    }

    attributes.push({
      key: attributeKey,
      value: dialogData[inputName]
    });
  }

  const formattedAttributes = buildFormattedAttributes(attributes);

  return `[${shortcode} ${formattedAttributes}]`;
}

/**
 * Resets all input fields in a dialog
 *
 * @param { Dialog } dialogApi - A TinyMCE Dialog instance
 * @returns { void }
 */
function resetTabData(dialogApi) {
  const inputData = dialogApi.getData();

  for (const inputName in inputData) {
    inputData[inputName] = '';
  }

  dialogApi.setData(inputData);
}

export {
  createTitlePanel,
  buildShortcodeStringFromData,
  resetTabData
};
