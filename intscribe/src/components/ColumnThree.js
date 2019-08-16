import React, { Component } from "react";
import { Link } from "react-router-dom";
import { ReactComponent as MarkSVG } from "../images/svg/mark.svg";

class ColumnThree extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoggedIn: true,
      interests: [],
      userInterests: [],
      interestStatus: ""
    };

    this.processInterestList = this.processInterestList.bind(this);
    this.toggleInterest = this.toggleInterest.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}interest/fetchAllInterests`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          interests: data.interests,
          userInterests: data.userInterests
        });
      });
  }

  toggleInterest = interest => e => {
    fetch(`${this.props.apiROOT}interest/addInterest/${interest}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          document.querySelector("#c3-" + interest).classList.toggle("green");
          document.querySelector(".c1-interest-count span").innerHTML =
            data.interestCount;
        }
      });
  };

  processInterestList() {
    if (this.state.isLoggedIn) {
      return this.state.interests.map(int => {
        if (this.state.userInterests.indexOf(int) >= 0) {
          return (
            <li key={int}>
              <Link to={`/interest/${int.replace(/ /g, "-")}`}>
                <p>{int}</p>
              </Link>
              <button
                className="green"
                id={`c3-${int}`}
                onClick={this.toggleInterest(int.replace(/ /g, "-"))}
              >
                <MarkSVG />
              </button>
            </li>
          );
        } else {
          return (
            <li key={int}>
              <Link to={`/interest/${int.replace(/ /g, "-")}`}>
                <p>{int}</p>
              </Link>
              <button
                className=""
                onClick={this.toggleInterest(int.replace(/ /g, "-"))}
                id={`c3-${int}`}
              >
                <MarkSVG />
              </button>
            </li>
          );
        }
      });
    } else {
      return this.state.interests.map(int => (
        <li key={int}>
          <Link to={`/interest/${int.replace(/ /g, "-")}`}>
            <p>{int}</p>
          </Link>
        </li>
      ));
    }
  }

  render() {
    return (
      <div className="columnThree">
        <ul className="c3-interest-lists">
          <li>Interests</li>
          {this.processInterestList()}
        </ul>
      </div>
    );
  }
}

export default ColumnThree;
