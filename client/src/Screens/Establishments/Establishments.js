import React, { Component } from 'react';
import Promise from "bluebird";

class Establishments extends Component {
    constructor(props) {
        super(props);
        this.state = {
            name : '',
            address : '',
        }
    }

    handleFormInput(k, v) {
        return new Promise((resolve) => {
            this.setState({
                [k] : v
            }, resolve(v));
        });
    }

    updateSearchCriteria() {
        // @TODO make a request here
    }

    render() {
        return (
            <main>
                <div className={"form-input"}>
                    <label>
                    <input
                        value={this.state.name}
                        onChange={(e) => {
                            this.handleFormInput('name', e.target.value)
                            .then(this.updateSearchCriteria.bind(this))
                        }}
                    />
                    </label>
                </div>
                <div className={"form-input"}>
                    <label>
                    <input
                        value={this.state.address}
                        onChange={(e) => {
                            this.handleFormInput('address', e.target.value)
                            .then(this.updateSearchCriteria.bind(this))
                        }}
                    />
                    </label>
                </div>
                <h1>WIP - this page will let you search for establishments when ompleted</h1>
            </main>
        );
    }
}

export default Establishments;
