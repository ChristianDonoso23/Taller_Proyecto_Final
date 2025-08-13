const createBooksPanel = () => {
    const authorStore = Ext.getStore("authorStore");
    if (!authorStore) {
        console.warn("authorStore no encontrado. Cargue el archivo author.js");
    }

    if (!Ext.ClassManager.isCreated('App.Model.Book')) {
        Ext.define("App.Model.Book", {
            extend: 'Ext.data.Model',
            idProperty: 'id',
            fields: [
                { name: "id", type: "int", persist: false }, // No enviar id en create
                { name: "title", type: "string" },
                { name: "description", type: "string" },
                { name: "publication_date", type: "date", dateFormat: 'Y-m-d' },  // Aquí el cambio
                { name: "isbn", type: "string" },
                { name: "gender", type: "string" },
                { name: "edition", type: "int" },
                { name: "author_id", mapping: 'author.id', type: "int" },
                {
                    name: "authorName",
                    convert: function (value, record) {
                        const a = record.get('author');
                        return a ? `${a.first_name} ${a.last_name}` : '';
                    }
                }
            ]
        });
    }

    const authorComboboxCfg = {
        xtype: 'combobox',
        name: 'author_id',
        fieldLabel: 'Author',
        store: authorStore,
        queryMode: 'local',
        displayField: 'fullName',
        valueField: 'id',
        forceSelection: true,
        allowBlank: false
    };

    const openDialog = (rec, isNew) => {
        const win = Ext.create('Ext.window.Window', {
            title: isNew ? 'Nuevo Libro' : 'Editar Libro',
            modal: true,
            width: 640,
            layout: 'fit',
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 12,
                    defaults: { anchor: '100%' },
                    items: [
                        { xtype: 'hiddenfield', name: 'id' },
                        { xtype: 'textfield', fieldLabel: 'Title', name: 'title', allowBlank: false },
                        { xtype: 'textarea', fieldLabel: 'Description', name: 'description' },
                        { xtype: 'datefield', fieldLabel: 'Publication Date', name: 'publication_date', format: 'Y-m-d', submitFormat: 'Y-m-d' }, // Aquí también el cambio
                        authorComboboxCfg,
                        { xtype: 'textfield', fieldLabel: 'ISBN', name: 'isbn' },
                        { xtype: 'textfield', fieldLabel: 'Gender', name: 'gender' },
                        { xtype: 'numberfield', fieldLabel: 'Edition', name: 'edition' }
                    ]
                }
            ],
            buttons: [
                {
                    text: 'Save',
                    handler() {
                        const form = this.up("window").down("form").getForm();
                        if (!form.isValid()) return;
                        form.updateRecord(rec);
                        if (isNew) bookStore.add(rec);
                        bookStore.sync({
                            success: () => {
                                Ext.Msg.alert('Success', 'Book saved successfully.');
                                this.up('window').close();
                            },
                            failure: () => {
                                Ext.Msg.alert('Error', 'Failed to save book.');
                            }
                        });
                    }
                },
                {
                    text: 'Cancel',
                    handler() { this.up('window').close(); }
                },
                {
                    text: 'Delete',
                    handler() {
                        const rec = this.up('grid').getSelection()[0];
                        if (!rec) return Ext.Msg.alert('Warning', 'Select a book');

                        Ext.Msg.confirm('Confirm', 'Delete this book?', btn => {
                            if (btn === 'yes') {
                                bookStore.remove(rec);
                                bookStore.sync({
                                    success: () => Ext.Msg.alert('Success', 'Deleted'),
                                    failure: () => Ext.Msg.alert('Error', 'Delete failed')
                                });
                            }
                        });
                    }
                }
            ]
        });
        win.down('form').loadRecord(rec);
        win.show();
    }

    const bookStore = Ext.create("Ext.data.Store", {
        storeId: "bookStore",
        model: "App.Model.Book",
        proxy: {
            type: "rest",
            url: "api/book.php",
            reader: {
                type: "json",
                rootProperty: ""
            },
            writer: { type: "json", writeAllFields: true },
            appendId: false
        },
        autoLoad: true,
        autoSync: false,
        listeners: {
            load: function (store, records, success) {
                console.log('Books store loaded:', success);
                console.log('First record data:', records[0] ? records[0].getData() : 'No records');
                if (records.length > 0) {
                    console.log('Raw data del primer libro:', records[0].raw);
                }
            }
        }
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: "Books",
        store: bookStore,
        itemId: "bookPanel",
        layout: "fit",
        columns: [
            { text: "Title", flex: 1, sortable: true, dataIndex: "title" },
            { text: "Description", flex: 2, sortable: false, dataIndex: "description" },
            {
                text: "Publication Date", flex: 1, sortable: true, dataIndex: "publication_date",
                renderer: function (value) {
                    if (value instanceof Date) {
                        return Ext.util.Format.date(value, 'Y-m-d');
                    }
                    return value;
                }
            },
            { text: "Author", flex: 1, sortable: true, dataIndex: "authorName" },
            { text: "ISBN", flex: 1, sortable: false, dataIndex: "isbn" },
            { text: "Gender", width: 120, sortable: false, dataIndex: "gender" },
            { text: "Edition", width: 80, sortable: false, dataIndex: "edition" }
        ],
        tbar: [
            {
                text: 'Add',
                handler: () => openDialog(Ext.create('App.Model.Book'), true)
            },
            {
                text: 'Update',
                handler: () => {
                    const grid = Ext.ComponentQuery.query('#bookPanel')[0];
                    const sel = grid.getSelection()[0];
                    if (!sel) return Ext.Msg.alert('Warning', 'Select a book');
                    openDialog(sel, false);
                }
            },
            {
                text: 'Delete',
                handler: () => {
                    const grid = Ext.ComponentQuery.query('#bookPanel')[0];
                    const sel = grid.getSelection()[0];
                    if (!sel) return Ext.Msg.alert('Warning', 'Select a book');
                    Ext.Msg.confirm('Confirm', 'Delete this book?', btn => {
                        if (btn === 'yes') {
                            bookStore.remove(sel);
                            bookStore.sync({
                                success: () => Ext.Msg.alert('Success', 'Deleted'),
                                failure: () => Ext.Msg.alert('Error', 'Delete failed')
                            });
                        }
                    });
                }
            }
        ]
    });

    return grid;
};

// Hacer la función disponible globalmente
window.createBooksPanel = createBooksPanel;