/**
 * Inserts link onto stage to add additional rows for Multiple relationships
 */

window.addEvent('domready', function() {
	// Taking all elements that have the data-prototype attribute
	$$('div[data-prototype]').each(function(element) {
		element.initRepeatable();
	}.bind(this));
});

/**
 * Extends elements so can apply new class to them
 */
Element.implement({
	initRepeatable: function(){
		return new RepeatableContainer(this);
	},
	initCollectionItem: function(){
		return new CollectionItem(this);
	}
});

/**
 * RepeatableContainer
 *
 * Description: RepeatableContainer is used to extend the DOM for the Symfony2 Form System
 * to allow easy setting and updating of content.
 *
 * @Author James Rickard <james@frodosghost.com>
 * @Version 0.1
 *
 */
RepeatableContainer = new Class({

    _protected:
    {
    	clone:            null,
        container:        null,
        collection:       null,
        add_button:       null,
        action_container: null
    },

    initialize: function(element)
    {
    	if (element.get('data-prototype') == null)
    	{
    		throw new Error("The prototype element is required but was not included in the Element or could not be found.");
    	}

    	this._protected.container        = element;
        var element_id                   = this._protected.container.get('id');
    	this._protected.clone            = this._protected.container.get('data-prototype');
    	this._protected.collection       = this._protected.container.getChildren('div.collection.controls');
		this._protected.add_button       = this._protected.container.getElement('[data-collection-add-btn='+element_id+']');
        this._protected.action_container = this._protected.add_button.getParent('div.collection.action'),

		this.setCollectionItems();
		this.addListeners();
    },

    // Adds new collection item to the stage
    addCollectionItem: function(event) {
    	event.stop();

		// Format variables for display
		var collection_children = this.get('collection').getChildren('div.collection-item').length,
		    updated_prototype = new Element('div', {
		    	html: this.get('clone').replace(/\$\$name\$\$/g, collection_children)
		    }).getFirst();

		    // Init collection item on new element
		    updated_prototype.initCollectionItem();

		this.get('action_container').grab(updated_prototype, 'before');
    },

    // Configure listening events
    addListeners: function(){
    	// Click listener for New Row button
    	this.get('add_button').addEvent('click', this.addCollectionItem.bind(this));
    },

    // Setup of the extisting Collection Items
    setCollectionItems: function() {
    	this.get('collection').getChildren('div.collection-item').each(function(element) {
    		element.initCollectionItem();
    	});
    },

    get: function(parameter) {
    	return (this._protected[parameter] !== 'undefined') ? this._protected[parameter] : false;
    }

});

/**
 * CollectionItem
 *
 * Description: CollectionItem is used to extend the DOM for the Symfony2 Form System
 * to allow adding adding removing of repeatable content
 *
 * @Author James Rickard <james@frodosghost.com>
 * @Version 0.1
 *
 */
CollectionItem = new Class({

	// Inheritance rules
    Implements: [Events],

    // Event listings
	STAGED:           'CollectionItem:staged',
	ENABLE_FIELDS:    'CollectionItem:enable_fields',
	DISABLE_FIELDS:   'CollectionItem:disable_fields',
	CANCEL_REMOVE:    'CollectionItem:cancel_remove',
	CONFIRM_REMOVAL:  'CollectionItem:confirm_removal',
	ADD_CONFIRMATION: 'CollectionItem:add_confirmation',

	// Protected variable names
	_protected: {
		container:        null,
		form_fields:      null,
		action_container: null,
		buttons:          {}
	},

	initialize: function(element)
	{
		this._protected.container = element;
		this._protected.form_fields = this.get('container').getElements('input, select, textarea, button');
		this._protected.action_container = new Element('div', {
			'class': 'action-container'
		});

		this.addListeners();
		this.configureButtons();
		this.configureActions();

		// Return instance
		return this;
	},

	// Adds container and remove button to stage
	configureActions: function() {
		this.get('buttons').remove_button.inject(this.get('action_container'));
		this.get('container').grab(this.get('action_container'), 'top');

		// Return instance
		return this;
	},

	// Sets div as staged for delete
	stagedDelete: function(element){
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

	// Configure listening events
	addListeners: function(){
		this.addEvent(this.STAGED, this.stagedDelete.bind(this));

		this.addEvent(this.DISABLE_FIELDS, this.disableFields.bind(this));
		this.addEvent(this.ENABLE_FIELDS, this.enableFields.bind(this));

		this.addEvent(this.ADD_CONFIRMATION, this.addConfirmation.bind(this));

		this.addEvent(this.CONFIRM_REMOVAL, this.removeCollectionItem.bind(this));
		this.addEvent(this.CANCEL_REMOVE, this.cancelRemoveRequest.bind(this));

		// Return instance
		return this;
	},

	get: function(parameter) {
        return (this._protected[parameter] !== 'undefined') ? this._protected[parameter] : false;
    },

    // Configures buttons that can be used within the element
    configureButtons: function() {
		this._protected.buttons = {
			delete_button: new Element('a', {
				'class': 'btn btn-danger btn-small',
				href:  '#',
				html:  'Confirm Removal',
				events: {
					'click': function(event){
						event.stop();

						this.fireEvent(this.CONFIRM_REMOVAL, event.target);
					}.bind(this)
				}
			}),
			retain_button: new Element('a', {
				'class': 'btn btn-success btn-small',
				href:  '#',
				html:  'Cancel',
				events: {
					'click': function(event){
						event.stop();

						this.fireEvent(this.CANCEL_REMOVE, event.target);
					}.bind(this)
				}
			}),
			remove_button: new Element('a', {
				'class': 'btn btn-danger',
				href:  '#',
				html:  '<i class="icon-remove icon-white"></i>',
				events: {
					'click': function(event){
						event.stop();

						this.fireEvent(this.STAGED, event.target);
					}.bind(this)
				}
			})
		};

		// Return instance
		return this;
	}

});
