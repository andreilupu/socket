import React from "react";
import ReactDOM from "react-dom";

import LinearProgressExampleSimple from './progress';

// import semantic from './semantic.min.js';

import Sections from './sections';

import { Grid, Image } from 'semantic-ui-react'
import { Container, Segment, Header, Loader, Dimmer, Form, Text, Button, Checkbox, Divider } from 'semantic-ui-react'

class AdminOptionsDashboard extends React.Component {

	constructor(props) {
		// this makes the this
		super(props);


		// get the current state localized by wordpress
		this.state = {
			loading: false,
			values: adminoptions.values
		};

		this.handleChange = this.handleChange.bind(this);
		this.inputHandleChange = this.inputHandleChange.bind(this);
		this.checkboxHandleChange = this.checkboxHandleChange.bind(this);
	}

	render() {
		let component = this;

		return <Segment>

			{ ( component.state.loading === true ) ?
				<div style={{"position" : 'absolute', "top": 0, "bottom": 0, "right": 0, "left": 0 }} >
					<Dimmer active>
						<Loader size='big' />

						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider inverted />
						<Divider horizontal inverted >Saving ... wait a second</Divider>

					</Dimmer>
				</div>
				:
				''
			}

			<Grid>{ Object.keys( adminoptions.config ).map(function( grid_key ){
				if ( typeof grid_key === "undefined" ) {
					return false;
				}

				var section_config = adminoptions.config[grid_key];

				// default grid sizes, doc this
				var sizes = { ...{ computer: 8, tablet: 16 }, ...section_config.sizes };

				var section = <Grid.Column key={grid_key} computer={sizes.computer} tablet={sizes.tablet} mobile={sizes.mobile}>
					<Segment>
					<Header as='h2' key={grid_key} content={section_config.label} subheader={section_config.desc} />

					<Form >
					{ Object.keys( section_config.items ).map(function( field_key ){

						let field = section_config.items[field_key],
							value = '';

						if ( typeof component.state.values[field_key] !== "undefined" ) {
							value = component.state.values[field_key];
						}

						var output = null;

						switch ( field.type ) {
							case 'text' : {

								output = <Form.Field key={field_key}>
									<label>{field.label}</label>
									<input placeholder='First Name' data-name={field_key} onInput={component.inputHandleChange} defaultValue={value} />
								</Form.Field>
								break;
							}

							case 'checkbox' : {
								value = component.validate_options_for_checkboxes(value);

								output = <Form.Field key={field_key}>
									<label>{field.label}</label>
									<Checkbox placeholder='First Name' data-name={field_key} onChange={component.checkboxHandleChange} defaultChecked={value} />
								</Form.Field>
								break;
							}

							case 'toggle' : {
								value = component.validate_options_for_checkboxes(value);

								output = <Form.Field key={field_key}>
									<label>{field.label}</label>
									<Checkbox toggle placeholder='First Name' data-name={field_key} onChange={component.checkboxHandleChange} defaultChecked={value} />
								</Form.Field>
								break;
							}

							default:
								break
						}

						return output
					})}
					</Form>
				</Segment>
				</Grid.Column>

				return section
			}) }</Grid>
		</Segment>
	}

	validate_options_for_checkboxes( value ) {

		if ( typeof value === 'number' ) {
			return ( value == 1 );
		}

		return ( value == 'true' || value == '1' );
	}

	htmlDecode(input) {
		var e = document.createElement('div');
		e.innerHTML = input;
		return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
	}

	inputHandleChange( e ) {
		e.persist();

		// every time a user types we need to delay tthis events until he stops typing
		this.delayedCallback(e);
	}

	componentWillMount () {

		this.delayedCallback = _.debounce(function (event) {
			// `event.target` is accessible now
			let component = this,
				name = event.target.dataset.name,
				value = event.target.value;

			if ( ! this.state.loading ) {

				this.async_loading(() => {

					jQuery.ajax({
						url: adminoptions.wp_rest.root + 'adminoptions/v1/option',
						method: 'POST',
						beforeSend: function (xhr) {
							xhr.setRequestHeader('X-WP-Nonce', adminoptions.wp_rest.nonce);
						},
						data: {
							'adminoptions_nonce': adminoptions.wp_rest.adminoptions_nonce,
							name: name,
							value: value
						}
					}).done(function (response) {

						let new_values = component.state.values;

						new_values[name] = value;

						component.setState({
							loading: false,
							values: new_values
						});

					}).error(function ( err ) {
						component.setState({
							loading: true,
						});
					});

				});
			}

		}, 1000);
	}


	checkboxHandleChange( e ) {
		let component = this,
			componentNode = ReactDOM.findDOMNode( e.target ).parentNode,
			input = componentNode.childNodes[0],
			name = componentNode.dataset.name,
			value = input.value;

		if ( ! this.state.loading ) {

			this.async_loading(() => {

				jQuery.ajax({
					url: adminoptions.wp_rest.root + 'adminoptions/v1/option',
					method: 'POST',
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-WP-Nonce', adminoptions.wp_rest.nonce);
					},
					data: {
						'adminoptions_nonce': adminoptions.wp_rest.adminoptions_nonce,
						name: name,
						value: (value === 'on' ) ? 1 : 0
					}
				}).done(function (response) {

					let new_values = component.state.values;

					new_values[name] = value;

					component.setState({
						loading: false,
						values: new_values
					});

				}).error(function ( err ) {
					component.setState({
						loading: true,
					});
				});

			});
		}

	}

	handleChange( e ) {

		console.log( this );

		console.debug(e.target.value);

	}

	async_loading = (cb) => {
		this.setState({loading: true}, () => {
			this.asyncTimer = setTimeout( cb, 500 );
		});
	};

	update_local_state($state) {
		this.setState($state, function () {
			jQuery.ajax({
				url: adminoptions.wp_rest.root + 'adminoptions/v1/react_state',
				method: 'POST',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', adminoptions.wp_rest.nonce);
				},
				data: {
					'adminoptions_nonce': adminoptions.wp_rest.adminoptions_nonce,
					state: this.state
				}
			}).done(function (response) {
				console.log(response);
			});
		});
	}

	add_notices = (state) => {
		var components = [];
		var install_data = JSON.parse(adminoptions.install_data);

		return components;
	}


}

export default (AdminOptionsDashboard);