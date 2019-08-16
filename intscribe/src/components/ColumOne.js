import React, { Component } from "react";
import { Link } from "react-router-dom";
import { ROOT, apiROOT } from "../config/config";

class ColumnOne extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoggedIn: true,
      loggedUser: {},
      username: "",
      userInterests: [],
      bookmarkCount: 0,
      postCount: 0,
      followerCount: 0,
      followingCount: 0
    };
  }

  componentDidMount() {
    fetch(`${apiROOT}userInfoCount`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          loggedUser: data.loggedUser,
          username: data.loggedUser.username,
          userInterests: data.userInterests,
          bookmarkCount: data.bookmarkCount,
          postCount: data.postCount,
          followerCount: data.followerCount,
          followingCount: data.followingCount
        });
      });
  }

  render() {
    return (
      <div className="columnOne">
        {this.state.isLoggedIn && (
          <React.Fragment>
            <div>
              {this.state.loggedUser.profile_image === "" ? (
                <div
                  className="c1-profile-image img"
                  style={{
                    backgroundImage:
                      "url(" + ROOT + "public/images/profile_pic/avatar.jpg)"
                  }}
                >
                  <img className="img-decoy" alt={this.state.username} />
                </div>
              ) : (
                <div
                  className="c1-profile-image img"
                  style={{
                    backgroundImage:
                      "url(" +
                      ROOT +
                      "public/images/profile_pic/" +
                      this.state.loggedUser.profile_image +
                      ")"
                  }}
                >
                  <img className="img-decoy" alt={this.state.username} />
                </div>
              )}

              <Link
                to={`/user/${this.state.username.replace(/ /g, "-")}-${
                  this.state.loggedUser.user_id
                }`}
              >
                <p className="c1-username pull-left">{this.state.username}</p>
              </Link>
              <div className="clear-float" />
            </div>

            <div className="c1-user-options">
              <Link
                to={`/user/${this.state.username.replace(/ /g, "-")}-${
                  this.state.loggedUser.user_id
                }/follower`}
              >
                <p>
                  Followers <span>{this.state.followerCount}</span>
                </p>
              </Link>
              <Link
                to={`/user/${this.state.username.replace(/ /g, "-")}-${
                  this.state.loggedUser.user_id
                }/following`}
              >
                <p className="c1-user-following-count">
                  Following <span>{this.state.followingCount}</span>
                </p>
              </Link>
            </div>

            <div className="c1-user-options">
              <Link
                to={`/user/${this.state.username.replace(/ /g, "-")}-${
                  this.state.loggedUser.user_id
                }/`}
              >
                <p className="c1-user-post-count">
                  Posts <span>{this.state.postCount}</span>
                </p>
              </Link>
            </div>

            <div className="c1-user-options">
              <Link
                to={`/user/${this.state.username.replace(/ /g, "-")}-${
                  this.state.loggedUser.user_id
                }/bookmark`}
              >
                <p className="c1-bookmark-count">
                  Bookmarks <span>{this.state.bookmarkCount}</span>
                </p>
              </Link>
            </div>

            <div className="c1-user-options">
              <Link
                to={`/user/${this.state.username.replace(/ /g, "-")}-${
                  this.state.loggedUser.user_id
                }/interest`}
              >
                <p className="c1-interest-count">
                  Interests <span>{this.state.userInterests}</span>
                </p>
              </Link>
            </div>
          </React.Fragment>
        )}

        {!this.state.isLoggedIn && (
          <h2
            style={{
              textAlign: "center",
              color: "#525252",
              marginTop: 150 + "px"
            }}
          >
            Please Login
          </h2>
        )}

        <footer>
          <p
            dangerouslySetInnerHTML={{ __html: "intscribe.com &copy; 2019" }}
          />{" "}
          <Link to={`/privacy`}>Privacy</Link> <Link to={`/terms`}>Terms</Link>{" "}
          <Link to={`/logout`}>Logout</Link>{" "}
        </footer>
      </div>
    );
  }
}

export default ColumnOne;
