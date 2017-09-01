import React, { Component } from 'react';

import Select from 'react-select';
import ReactTable from 'react-table';
import 'react-table/react-table.css';
import 'react-select/dist/react-select.css';
import './App.css';

const request = require('request');

class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            select_value : null,
            select_options : [],
            table_isLoading : false,
            table_data : [],
        };
    }

    /**
     * on initial load, grab hold of the list of local authorities
     * @return {void}
     */
    componentWillMount() {
        request({
            url: 'http://192.168.33.10/v1/local-authorities',
            headers: {
                Authorization: 'TOKEN-HERE'
            }
        }, (error, response, body) => {
			if (body == null) {
				return false;
			}
            let data = JSON.parse(body);
            this.setState({ select_options: data.payload.localAuthorities });
        });
    }

    /**
     * given a local authority ID, grab the establishment profile for that ID from
     * the API and load it into state
     *
     * @param  {string|int} id a local authority ID
     * @return {void}
     */
    updateLocalAuthorityEstablishmentsProfile(id) {
        request({
            url: 'http://192.168.33.10/v1/establishments-profile/' + id,
            headers: {
                Authorization: 'TOKEN-HERE'
            }
        }, (error, response, body) => {
            let data = JSON.parse(body);

            // add an extra row to represent the totals
            data.payload.tableData.push({
                "rating" : "",
                "count" : data.payload.total,
                "percentage" : 100,
            })
            this.setState({ table_data : data.payload.tableData, table_isLoading: false })
        });
    }

    render() {
        const columns = [{
            id: 'rating',
            Header: 'Rating',
            accessor: 'rating'
        }, {
            id: 'count',
            Header: 'Count',
            accessor: 'count',
        }, {
            id: 'percentage',
            Header: 'Percentage of Distribution',
            accessor: 'percentage'
        }];

        return (
            <main>
                <section>
                    <Select
                    name="form-field-name"
                    value={this.state.select_value}
                    options={this.state.select_options}
                    placeholder="Please select or search for a local authority..."
                    onChange={v => {
                        this.setState({
                            select_value: v.value,
                            table_data: [],
                            table_isLoading: true
                        });
                        this.updateLocalAuthorityEstablishmentsProfile(v.value);
                    }}
                    />
                </section>

                <section>
                    <ReactTable
                    data={this.state.table_data}
                    columns={columns}
                    loading={this.state.table_isLoading}
                    showPagination={false}
                    />
                </section>

            </main>
        );
    }
}

export default App;
