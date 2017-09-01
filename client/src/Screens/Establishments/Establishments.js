import React, { Component } from 'react';

class Establishments extends Component {
    constructor(props) {
        super(props);

        this.state = {
            name : null,
            address : null,
        }
    }

    updateSearchCriteria() {
        
    }

    render() {
        return (
            <main>
                <section>
                    <label>
                        Establishment Name
                        <input
                        onChange={(e) => {
                            this.setState({name : e.target.value}, () => {
                                this.updateSearchCriteria();
                            });
                        }}
                        value={this.state.name}
                        />
                    </label>
                    <label>
                        Establishment Address
                        <input
                        onChange={(e) => {
                            this.setState({address : e.target.value}, () => {
                                this.updateSearchCriteria();
                            });
                        }}
                        value={this.state.address}
                        />
                    </label>
                </section>
                <h1>WIP - this page will let you search for establishments when ompleted</h1>
            </main>
        );
    }
}

export default Establishments;
