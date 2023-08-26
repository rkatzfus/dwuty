# example php file

![header](https://datatableswebutility.net/img/docs/header.jpg)

1. - load libraries
   - get environment
2. - link bootstrap, datatables & select2 CSS
   - [load jQuery](https://api.jquery.com/)

![header](https://datatableswebutility.net/img/docs/body_1.jpg)

3. main config
4. create column(s)
5. initialize table
6. - default column order
   - load [datatables Extensions](https://datatables.net/extensions/index)

---

## 3. main config (detail)

![header](https://datatableswebutility.net/img/docs/basecfg_1.jpg)

1. optional: required for "DROPDOWN_MULTI" column(s) -> column & datatypes
2. optional: debug mode
3. - **type**: database type (mysql | sqlsrv | pgsql)
   - **credentials**: (.env)
4. - **create** activ = true/false -> create record
   - **update** activ = true/false -> update record
   - **delete** activ = true/false -> delete record
   - optional: "dropdown_multi" -> tbd
5. - **datasource**: 'FROM' -> sql syntax with table joins
   - **primary_key**: primary key from '**datasource**'
   - **lang_iso_639_1**: table language de/en
6. passing config to table

![header](https://datatableswebutility.net/img/docs/basecfg_2.jpg)

7. optional: config "CHECKBOX" column -> column & datatypes

   - **ORDERABLE**: true/false
   - **SEARCHABLE**: true/false

8. optional: config "DROPDOWN" column -> column & datatypes

   - **colums => id**: primary key from the [Lookup table](https://en.wikipedia.org/wiki/Lookup_table)
   - **colums => text**: data from the Lookup table
   - **datasource**: 'FROM' -> sql syntax from the Lookup Table

9. optional: config "DROPDOWN_MULTI" column -> column & datatypes

   - tbd

---

## 4. create column(s) (detail)

![header](https://datatableswebutility.net/img/docs/create_column.jpg)

1. sql name with alias
2. shown column name
3. columntype -> column & datatypes
4. datatype -> column & datatypes
5. optional: array for datatype configuration -> depends on datatype

## 6. column order & datatables Extensions

![header](https://datatableswebutility.net/img/docs/sort_ext_1.jpg)

1. Default column order
   - **column_no**: column index, starts with 0
   - **direction**: asc/desc
2. [datatables Extensions](https://datatables.net/extensions/index) - first install [Composer datatables Extension](https://packagist.org/packages/datatables.net/datatables.net?query=datatables.net%20extensions) and link CSS in the html header, top **(2)**

# column- / datatypes

| columntype | effect                      |
| ---------- | --------------------------- |
| VIEW       | read-only content           |
| EDIT       | editable/ deletable content |

---

| datatyp        | effect                     |                                                                               |
| -------------- | -------------------------- | ----------------------------------------------------------------------------- |
| TEXT           | string                     | ![col_text](https://datatableswebutility.net/img/docs/col_text.jpg)           |
| EMAIL          | email                      | ![col_mail](https://datatableswebutility.net/img/docs/col_mail.jpg)           |
| CHECKBOX       | checkbox                   | ![col_check](https://datatableswebutility.net/img/docs/col_check.jpg)         |
| LINK           | url link                   | ![col_link](https://datatableswebutility.net/img/docs/col_link.jpg)           |
| LINK_BUTTON    | url link button            | ![col_link_btn](https://datatableswebutility.net/img/docs/col_link_btn.jpg)   |
| COLOR          | colorpicker                | ![col_col](https://datatableswebutility.net/img/docs/col_col.jpg)             |
| DROPDOWN       | dropdown - single choice   | ![col_drp](https://datatableswebutility.net/img/docs/col_drp.jpg)             |
| DROPDOWN_MULTI | dropdown - multiple choice | ![col_drp_mul](https://datatableswebutility.net/img/docs/col_drp_mul.jpg)     |
| DATE           | datepicker                 | ![col_date](https://datatableswebutility.net/img/docs/col_date.jpg)           |
| DATETIME       | date-time picker           | ![col_date_time](https://datatableswebutility.net/img/docs/col_date_time.jpg) |
