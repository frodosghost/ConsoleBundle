/**
 * Inserts link onto stage to add additional rows for Multiple relationships
 *
 * Use with the collection_widget.html.twig
 */

/**
 * Extends elements so can apply new class to them
 */
Element.implement({
    initRepeatable: function(parameters){
        return new RepeatableContainer(this, parameters);
    },
    initCollectionItem: function(parameters){
        return new CollectionItem(this, parameters);
    }
});

/**
 * Holds Collection elements used in repeatable container.
 *
 * @type    {Object}
 * @Author  James Rickard <james@frodosghost.com>
 * @Version 0.1
 */
Collection = new Class({

    _protected: {
        items: [],
    },

    add: function(element) {
        this._protected.items.push(element);

        return this;
    },

    get: function(index) {
        return (this._protected.items[index] !== null) ? this._protected.items[index] : null;
    },

    all: function() {
        return this._protected.items;
    },

    remove: function(element) {
        this._protected.items.erase(element);
    },

    length: function() {
        return this._protected.items.length;
    },

    getIndex: function (element) {
        return this._protected.items.indexOf(element);
    },

    getNext: function(element) {
        var index = this.getIndex(element);
        var next = this.get((index+1));

        return (next !== null) ? next : null;
    },

    getPrevious: function(element) {
        var index = this.getIndex(element);
        var previous = this.get((index-1));

        return (previous !== null) ? previous : null;
    },

    updateIndex: function(target, sibling) {
        var targetIndex = this.getIndex(target);
        var siblingIndex = this.getIndex(sibling);

        this._protected.items[targetIndex] = sibling;
        this._protected.items[siblingIndex] = target;

        return this;
    }

});

/**
 * RepeatableContainer
 *
 * Description: RepeatableContainer is used to extend the DOM for the Symfony2 Form System
 * to allow easy setting and updating of content.
 *
 * @Author James Rickard <james@frodosghost.com>
 * @Version 0.2
 *
 */
RepeatableContainer = new Class({

    // Inheritance rules
    Implements: [Events],

    // Instance parameters
    _parameters:
    {
        sequence: false // Optional: (Boolean) Set if sequence has been enabled on collection
    },

    // Protected parameters
    _protected:
    {
        // Cloned Element for Adding New Row
        clone:            null,
        // All items as set by the elements when constructed
        items:            null,
        // Main Collection Container
        container:        null,
        // Items that have been initialised
        collection:       new Collection(),
        // Button to Add new Row
        add_button:       null,
        // Container that new rows are added into. Separate from existing items
        action_container: null
    },

    initialize: function(element, parameters) {
        if (element.get('data-prototype') == null)
        {
            throw new Error("The prototype element is required but was not included in the Element or could not be found.");
        }

        // Override default parameters
        this.overrideDefaultParameters(parameters || {});

        this._protected.container        = element;
        var element_id                   = this._protected.container.get('id');

        this._protected.clone            = this._protected.container.get('data-prototype');
        this._protected.items            = this._protected.container.getElements('.item');
        this._protected.add_button       = this._protected.container.getNext('div.main.controls a.add');
        this._protected.action_container = this._protected.container.getElement('.fields-list'),

        this.addListeners();
        this.setCollectionItems();
    },

    overrideDefaultParameters: function (parameters) {
        for (var parameter in this._parameters) {
            if (typeof parameters[parameter] !== 'undefined') {
               this._parameters[parameter] = parameters[parameter];
            }
        }

        return this;
    },

    // Adds new collection item to the stage
    addCollectionItem: function(event) {
        event.stop();

        // Format variables for display
        var collection_children = this.getter('collection').length();
        var updated_prototype = new Element('div', {
                html: this.getter('clone').replace(/__name__/g, collection_children)
            }).getFirst();

        // Init collection item on new element
        this.getter('collection').add(
            updated_prototype.initCollectionItem({ parent: this, sequence: this.accessor('sequence') })
        );

        if (this.accessor('sequence')) {
            this.fireEvent(RepeatableContainerNS.SEQUENCE_UPDATE_FIELDS);
        }

        this.getter('action_container').grab(updated_prototype);
    },

    // Configure listening events
    addListeners: function() {
        // Click listener for New Row button
        this.getter('add_button').addEvent('click', this.addCollectionItem.bind(this));

        this.addEvent(RepeatableContainerNS.SEQUENCE_UP, this.sequenceUp.bind(this));
        this.addEvent(RepeatableContainerNS.SEQUENCE_DOWN, this.sequenceDown.bind(this));
        this.addEvent(RepeatableContainerNS.SEQUENCE_INIT_BUTTONS, this.sequenceButtons.bind(this));
        this.addEvent(RepeatableContainerNS.SEQUENCE_REMOVE_FIELD, this.removeItem.bind(this));
        this.addEvent(RepeatableContainerNS.SEQUENCE_UPDATE_FIELDS, this.updateSequenceFields.bind(this));
    },

    // Setup of the extisting Collection Items
    setCollectionItems: function() {
        this.getter('items').each(function(element) {
            this.getter('collection').add(
                element.initCollectionItem({ parent: this, sequence: this.accessor('sequence') })
            );
        }.bind(this));

        if (this.accessor('sequence')) {
            this.fireEvent(RepeatableContainerNS.SEQUENCE_UPDATE_FIELDS);
        }
    },

    sequenceUp: function(element, event) {
        var siblingElement = this.getter('collection').getPrevious(element);

        var item = element.get('container');
        var previous = (typeof siblingElement !== 'undefined') ? siblingElement.get('container') : null;

        if (previous !== null) {
            previous.grab(item, 'before');

            this.getter('collection').updateIndex(element, siblingElement);
            this.fireEvent(RepeatableContainerNS.SEQUENCE_UPDATE_FIELDS);
        }
    },

    sequenceDown: function(element, event) {
        var siblingElement = this.getter('collection').getNext(element);

        var item = element.get('container');
        var next = (typeof siblingElement !== 'undefined') ? siblingElement.get('container') : null;

        if (next !== null) {
            next.grab(item, 'after');

            this.getter('collection').updateIndex(element, siblingElement);
            this.fireEvent(RepeatableContainerNS.SEQUENCE_UPDATE_FIELDS);
        }
    },

    /**
     * Remove Item from the collection
     *
     * @param  {Object} element
     */
    removeItem: function(element) {
        this.getter('collection').remove(element);

        if (this.accessor('sequence')) {
            this.fireEvent(RepeatableContainerNS.SEQUENCE_UPDATE_FIELDS);
        }
    },

    /**
     * Updated sequence field values
     */
    updateSequenceFields: function() {
        this.getter('collection').all().each(function(element, index) {
            this.fireEvent(RepeatableContainerNS.SEQUENCE_INIT_BUTTONS, [ element ]);

            element.get('sequence_field').setProperty('value', (index+1));
        }.bind(this));
    },

    /**
     * Determines if the Up/Down button should be displayed by position in list
     */
    sequenceButtons: function(element) {
        var item = element.get('container');

        var total = this.getter('collection').length();
        var index = this.getter('collection').getIndex(element);

        if (index == 0) {
            item.getElement('.up').hide();
        } else {
            item.getElement('.up').show();
        }

        if ((index+1) == total) {
            item.getElement('.down').hide();
        } else {
            item.getElement('.down').show();
        }
    },

    getter: function(parameter) {
        return (this._protected[parameter] !== 'undefined') ? this._protected[parameter] : false;
    },

    accessor: function (parameter) {
        return (this._parameters[parameter] !== 'undefined') ? this._parameters[parameter] : false;
    }

});

/**
 * CollectionItem
 *
 * Description: CollectionItem is used to extend the DOM for the Symfony2 Form System
 * to allow adding adding removing of repeatable content
 *
 * @Author James Rickard <james@frodosghost.com>
 * @Version 0.2
 */
CollectionItem = new Class({

    // Inheritance rules
    Implements: [Events],

    // Instance parameters
    _parameters:
    {
        sequence: false, // Optional: (Boolean) Set if sequence has been enabled on collection
        parent: null     // Optional: (Object) Parent Class of RepeatableContainer
    },

    // Protected variable names
    _protected: {
        container:          null,
        form_fields:        null,
        action_container:   null,
        sequence_container: null,
        sequence_field:     null,
        buttons:            {},
    },

    // Event listings
    STAGED:           'CollectionItem:staged',
    ENABLE_FIELDS:    'CollectionItem:enable_fields',
    DISABLE_FIELDS:   'CollectionItem:disable_fields',
    CANCEL_REMOVE:    'CollectionItem:cancel_remove',
    CONFIRM_REMOVAL:  'CollectionItem:confirm_removal',
    ADD_CONFIRMATION: 'CollectionItem:add_confirmation',
    ENABLE_SEQUENCE:  'CollectionItem:enable_sequence',

    initialize: function(element, parameters) {
        this._protected.container = element;
        this._protected.form_fields = this.get('container').getElements('input, select, textarea, button');
        this._protected.action_container = element.getElement('.controls');
        this._protected.sequence_container = element.getElement('.order .sequence');

        // Override default parameters
        this.overrideDefaultParameters(parameters || {});

        this.addListeners();
        this.configureButtons();
        this.configureActions();

        // Return instance
        return this;
    },

    overrideDefaultParameters: function (parameters) {
        for (var parameter in this._parameters) {
            if (typeof parameters[parameter] !== 'undefined') {
               this._parameters[parameter] = parameters[parameter];
            }
        }

        return this;
    },

    // Adds container and remove button to stage
    configureActions: function() {
        this.get('container').grab(this.get('action_container'), 'bottom');

        if (this.accessor('sequence')) {
            this.initSequenceField();

            this.fireEvent(this.ENABLE_SEQUENCE, this);
        }

        // Return instance
        return this;
    },

    // Sets div as staged for delete
    stagedDelete: function(element) {
        // Add staged class to container element
        this.get('container').addClass('staged');

        this.fireEvent(this.DISABLE_FIELDS, this);
        this.fireEvent(this.ADD_CONFIRMATION, this);
    },

    // Adds confirmation buttons to stage
    addConfirmation: function() {
        this.get('buttons').delete_button.show().inject(this.get('action_container'));
        this.get('buttons').retain_button.show().inject(this.get('action_container'));

        this.get('buttons').remove_button.hide();
    },

    // Removes current element from stage
    removeCollectionItem: function() {
        this.fireEvent(this.ENABLE_FIELDS, this);

        this.accessor('parent').fireEvent(RepeatableContainerNS.SEQUENCE_REMOVE_FIELD, [ this ]);
        this.get('container').destroy();
    },

    // Returns form fields to editable stage and removes confirmation buttons
    cancelRemoveRequest: function() {
        this.get('buttons').delete_button.hide();
        this.get('buttons').retain_button.hide();

        this.get('buttons').remove_button.show().inject(this.get('action_container'));

        this.get('container').removeClass('staged');
        this.fireEvent(this.ENABLE_FIELDS, this);
    },

    // Disable all fields within an element
    disableFields: function() {
        this.get('form_fields').each(function(element){
            element.set('disabled', 'disabled');
        });
    },

    // Enable all fields within an element
    enableFields: function() {
        this.get('form_fields').each(function(element){
            element.removeProperty('disabled');
        });
    },

    initSequence: function (element) {
        var up_button = this.get('sequence_container').getElement('a.up');
        var down_button = this.get('sequence_container').getElement('a.down');

        up_button.addEvent('click', function (event) {
            event.stop();

            this.accessor('parent').fireEvent(RepeatableContainerNS.SEQUENCE_UP, [ this, event ]);
        }.bind(this));

        down_button.addEvent('click', function (event) {
            event.stop();

            this.accessor('parent').fireEvent(RepeatableContainerNS.SEQUENCE_DOWN, [ this, event ]);
        }.bind(this));

        this.accessor('parent').fireEvent(RepeatableContainerNS.SEQUENCE_INIT_BUTTONS, [ this ]);
        this.get('sequence_container').setStyle('display', 'block');
    },

    /**
     * Sets up the Sequence Input field so can be accessed when moved
     */
    initSequenceField: function() {
        var sequenceFields = this.get('form_fields').filter(function (item, index) {
            return item.match('[name*=sequence]');
        });

        this._protected.sequence_field = (typeof sequenceFields[0] == 'object') ? sequenceFields[0] : null;

        return this;
    },

    // Configure listening events
    addListeners: function() {
        this.addEvent(this.STAGED, this.stagedDelete.bind(this));

        this.addEvent(this.DISABLE_FIELDS, this.disableFields.bind(this));
        this.addEvent(this.ENABLE_FIELDS, this.enableFields.bind(this));

        this.addEvent(this.ADD_CONFIRMATION, this.addConfirmation.bind(this));

        this.addEvent(this.CONFIRM_REMOVAL, this.removeCollectionItem.bind(this));
        this.addEvent(this.CANCEL_REMOVE, this.cancelRemoveRequest.bind(this));

        this.addEvent(this.ENABLE_SEQUENCE, this.initSequence.bind(this));

        // Return instance
        return this;
    },

    get: function(parameter) {
        return (this._protected[parameter] !== 'undefined') ? this._protected[parameter] : false;
    },

    accessor: function (parameter) {
        return (this._parameters[parameter] !== 'undefined') ? this._parameters[parameter] : false;
    },

    // Configures buttons that can be used within the element
    configureButtons: function() {
        this._protected.buttons = {
            delete_button: new Element('a', {
                'class': 'pure-button small delete',
                href:  'javascript:void()',
                html:  'Delete',
                events: {
                    'click': function(event){
                        event.stop();

                        this.fireEvent(this.CONFIRM_REMOVAL, event.target);
                    }.bind(this)
                }
            }),
            retain_button: new Element('a', {
                'class': 'pure-button small remove',
                href:  'javascript:void()',
                html:  'Cancel',
                events: {
                    'click': function(event){
                        event.stop();

                        this.fireEvent(this.CANCEL_REMOVE, event.target);
                    }.bind(this)
                }
            }),
            remove_button: this.get('action_container')
                .getElement('a.remove')
                .addEvent('click', function (event) {
                    event.stop();

                    this.fireEvent(this.STAGED, event.target);
                }.bind(this))
        };

        // Return instance
        return this;
    }

});

/**
 * Namespaced container for holding Events used for Sequence
 *
 * @type    {Object}
 * @Author  James Rickard <james@frodosghost.com>
 * @Version 0.1
 */
var RepeatableContainerNS = {
    // Event listings
    SEQUENCE_UP:           'RepeatableContainer:sequence_up',
    SEQUENCE_DOWN:         'RepeatableContainer:sequence_down',
    SEQUENCE_REMOVE_FIELD: 'RepeatableContainer:sequence_remove_field',
    SEQUENCE_INIT_BUTTONS: 'RepeatableContainer:sequence_init_buttons',
    SEQUENCE_UPDATE_FIELDS: 'RepeatableContainer:sequence_update_fields'
}
