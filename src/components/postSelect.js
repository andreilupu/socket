import React from "react"
import ReactDOM from "react-dom"
import PropTypes from 'prop-types'
// import _ from 'lodash'

import {
	Form,
	Dropdown
} from 'semantic-ui-react'

export default class SocketPostSelect extends React.Component {

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
			posts: [],
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

		output = <Form.Field className="post_type_select" >
			<Dropdown
				placeholder={this.props.placeholder}
				search
				selection
				closeOnBlur={false}
				multiple={true}
				loading={this.state.loading}
				defaultValue={value}
				options={this.state.posts}
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

		// load all the posts
		wp.api.loadPromise.done( function() {
			var wpPosts = new wp.api.collections.Posts(),
				posts = [];

			wpPosts.fetch( { data: { per_page: 100 } } ).done(function (models) {
				{Object.keys(models).map(function ( i ) {
					let model = models[i];

					posts.push({ key: model.id, value: model.id.toString(), text: model.title.rendered });
				})}

				component.setState( { posts: posts, loading: false } );
			});
		});
	}
}
