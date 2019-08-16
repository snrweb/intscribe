import React, { Component } from "react";
import { Link } from "react-router-dom";

class Following extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <React.Fragment>
        {this.props.followings.map(f => (
          <div className="int-follow" key={f.user_id}>
            {f.profile_image === "" ? (
              <div
                className="f-profile-image img"
                style={{
                  backgroundImage:
                    "url(" +
                    this.props.root +
                    "public/images/profile_pic/avatar.jpg)"
                }}
              >
                <img className="img-decoy" alt={f.username} />
              </div>
            ) : (
              f.profile_image !== "" && (
                <div
                  className="f-profile-image img"
                  style={{
                    backgroundImage:
                      "url(" +
                      this.props.root +
                      "public/images/profile_pic/" +
                      f.profile_image +
                      ")"
                  }}
                >
                  <img className="img-decoy" alt={f.username} />
                </div>
              )
            )}

            <span>
              <Link to={`/user/${f.username.replace(/ /g, "-")}-${f.user_id}`}>
                {f.username}
              </Link>
            </span>

            {parseInt(f.user_id) !== this.props.isLoggedInUserID && (
              <button
                id={`follow-${f.user_id}`}
                onClick={this.props.toggleFollow(f.user_id)}
              >
                Following
              </button>
            )}
          </div>
        ))}

        <div className="clear-float" />
      </React.Fragment>
    );
  }
}

export default Following;
