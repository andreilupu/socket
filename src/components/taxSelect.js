import React from "react"
import ReactDOM from "react-dom"
import PropTypes from 'prop-types'
import {
	Form,
	Dropdown
} from 'semantic-ui-react'

export default class SocketTaxSelect extends React.Component {
	static propTypes = {
		name: PropTypes.string,
		value: PropTypes.array,
		setup_loading_flag: PropTypes.func
	}

	constructor(props) {
		// this makes the this
		super(props);

		// get the current state localized by wordpress
		this.state = {
			loading: true,
			terms: [],
			name: null,
			value: this.props.value
		};

		this.handleClose = this.handleClose.bind(this);
	}

	render() {
		let component = this,
			output = null,
			value = this.props.value;

		if ( '' === value ) {
			value = []
		}

		// console.log( this.state );

		output = <Form.Field className="post_type_select" >
			<Dropdown
				placeholder={this.props.placeholder}
				search
				selection
				multiple={true}
				loading={this.state.loading}
				defaultValue={value}
				options={this.state.terms}
				onChange={component.handleChange}
				onClose={component.handleClose}
			/>
		</Form.Field>

		return output;
	}

	handleChange = (e, { value }) => {
		this.setState({ value });
	}

	// on close we want to save the data
	handleClose(e){
		let component = this,
			value = this.state.value

		if ( '' === value || [] === value ) {
			return;
		}

		component.props.setup_loading_flag( true );
		jQuery.ajax({
			url: socket.wp_rest.root + 'socket/v1/option',
			method: 'POST',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', socket.wp_rest.nonce);
			},
			data: {
				'socket_nonce': socket.wp_rest.socket_nonce,
				name: this.props.name,
				value: value
			}
		}).done(function (response) {
			// let new_values = component.state.values;
			console.log(response);
			component.props.setup_loading_flag( false );
		}).error(function (err) {
			console.log(err);
			component.props.setup_loading_flag( false );
		});
	}

	componentWillMount(){
		if ( ! this.state.loading ) {
			return false;
		}

		let component = this;

		wp.api.loadPromise.done( function() {
			let catsCollection = new wp.api.collections.Categories(),
			terms = [];

			catsCollection.fetch( { data: { per_page: 100 } } ).done(function (models) {
				{Object.keys(models).map(function ( i ) {
					var model =models[i];
					terms.push({key: model.id, value: model.id.toString(), text: model.name});
				})}

				component.setState({ terms: terms, loading: false });
			});
		});
	}
}