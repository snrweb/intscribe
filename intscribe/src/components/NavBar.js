import React, { Component } from "react";
import { Link } from "react-router-dom";
import { ReactComponent as SearchSVG } from "../images/svg/search.svg";
import { ReactComponent as NotifSVG } from "../images/svg/notif.svg";

class NavBar extends Component {
  constructor(props) {
    super(props);

    this.state = {
      notificationCount: 0,
      loggedUser: {},
      userID: 0,
      username: "",
      profileImage: ""
    };
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}nav`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          notificationCount: data.notificationCount,
          loggedUser: data.loggedUser,
          userID: data.loggedUser.user_id,
          username: data.loggedUser.username,
          profileImage: data.loggedUser.profile_image
        });
      });
  }

  render() {
    return (
      <nav className="navBar">
        <Link to="/">
          <div
            className="navBarLogo"
            style={{
              backgroundImage:
                "url(" + this.props.root + "public/images/logo/logo.jpg)"
            }}
          />
        </Link>

        <div className="navBarSearch">
          <input
            style={{ height: 26 + "px" }}
            className="navBarSearch-input"
            type="text"
            placeholder="Search users, posts..."
          />
        </div>

        <div className="navBarSearch-icon">
          <SearchSVG />
        </div>

        {this.props.isLoggedIn && (
          <React.Fragment>
            <Link
              to={`/user/${this.state.username.replace(/ /g, "-")}-${
                this.state.userID
              }/`}
            >
              <div className="navBarProfile">
                {this.state.loggedUser.profile_image === "" ? (
                  <div
                    className="img"
                    style={{
                      backgroundImage:
                        "url(" +
                        this.props.root +
                        "public/images/profile_pic/avatar.jpg)"
                    }}
                  >
                    <img className="img-decoy" alt={this.state.username} />
                  </div>
                ) : (
                  <div
                    className="img"
                    style={{
                      backgroundImage:
                        "url(" +
                        this.props.root +
                        "public/images/profile_pic/" +
                        this.state.loggedUser.profile_image +
                        ")"
                    }}
                  >
                    <img className="img-decoy" alt={this.state.username} />
                  </div>
                )}
                <p className="navbar-username">
                  {this.state.username.split(" ")[0]}
                </p>
              </div>
            </Link>

            <Link to={`/notification`}>
              <div className="navBarNotif">
                <NotifSVG />
                <span className="navBarNotifCount">
                  {this.state.notificationCount}
                </span>
              </div>
            </Link>
          </React.Fragment>
        )}

        {!this.props.isLoggedIn && (
          <React.Fragment>
            <a href={`${this.props.root}register`}>
              <div className="navBarRegister">
                <button className="btn">Get started</button>
              </div>
            </a>

            <a href={`${this.props.root}login`}>
              <div className="navBarLogin">
                <button className="btn">Login</button>
              </div>
            </a>
          </React.Fragment>
        )}
      </nav>
    );
  }
}

export default NavBar;
