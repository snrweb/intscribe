import React, { Component } from "react";
import { Link, Route } from "react-router-dom";
import { ReactComponent as MarkSVG } from "../images/svg/mark.svg";
import Posts from "../components/Posts";
import Follower from "../components/Follower";
import Following from "../components/Following";
import UserFormEditor from "../editors/UserFormEditor";

import { Store } from "../store/Store";

class User extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isFollowing: false,
      user: {},
      username: "",
      cmp: "",
      posts: [],
      interests: [],
      followings: [],
      followers: [],
      followingLite: [],
      bookmarkCount: 0,
      postCount: 0,
      followerCount: 0,
      followingCount: 0,
      interestCount: 0,
      requestSent: false,
      counter: 10,
      scrollTo: 0
    };

    this.beforeListFetch = this.beforeListFetch.bind(this);
    this.fetchPostList = this.fetchPostList.bind(this);
    this.fetchBookmarkedPostList = this.fetchBookmarkedPostList.bind(this);
    this.setScrollPosition = this.setScrollPosition.bind(this);
  }

  componentDidMount() {
    window.addEventListener("scroll", this.setScrollPosition);

    let urlAry = this.props.match.params.user.split("-");
    let userID = urlAry[urlAry.length - 1];
    userID = "user" + userID;

    console.log(Store);

    if (userID in Store) {
      this.setState({
        user: Store[userID]["user"],
        username: Store[userID]["username"],
        bookmarkCount: Store[userID]["bookmarkCount"],
        postCount: Store[userID]["postCount"],
        followerCount: Store[userID]["followerCount"],
        followingCount: Store[userID]["followingCount"],
        interestCount: Store[userID]["interestCount"],
        scrollTo: Store[userID]["scrollTo"]
      });
    }

    if (!(userID in Store) || Store.userChange === true) {
      console.log(Store.userChange);
      fetch(
        `${this.props.apiROOT}user/setUserDetails/${
          this.props.match.params.user
        }`
      )
        .then(res => res.json())
        .then(data => {
          this.setState({
            user: data.user,
            username: data.user.username,
            bookmarkCount: data.bookmarkCount,
            postCount: data.postCount,
            followerCount: data.followerCount,
            followingCount: data.followingCount,
            interestCount: data.interestCount
          });
        });
    }

    fetch(
      `${this.props.apiROOT}user/${this.props.match.params.user}/${
        this.props.match.params.suser
      }`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          cmp: data.cmp,
          posts: data.posts,
          interests: data.interests,
          followings: data.followings,
          followers: data.followers,
          followingLite: data.followingLite,
          isFollowing: data.isFollowing
        });
      });
  }

  componentWillUnmount() {
    let urlAry = this.props.match.params.user.split("-");
    let userID = urlAry[urlAry.length - 1];
    userID = "user" + userID;
    Store[userID] = this.state;

    //This is a check to prevent the component from using the
    //data in the Store object
    Store.userChange = false;

    window.removeEventListener("scroll", this.setScrollPosition);
  }

  setScrollPosition() {
    let position = window.pageYOffset;
    this.setState({ scrollTo: position });
  }

  beforeListFetch() {
    if (this.state.requestSent) return;

    if (this.state.cmp === "bookmark") {
      setTimeout(this.fetchBookmarkedPostList, 2000);
    } else {
      setTimeout(this.fetchPostList, 2000);
    }
    this.setState({ requestSent: true });
  }

  fetchPostList() {
    fetch(
      `${this.props.apiROOT}post/userPost/${this.props.match.params.user}/${
        this.state.counter
      }`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          posts: this.state.posts.concat(data.posts)
        });
        if (data.posts.length > 0) {
          this.setState({
            requestSent: false,
            counter: this.state.counter + 10
          });
        }
      });
  }

  fetchBookmarkedPostList() {
    fetch(
      `${this.props.apiROOT}post/bookmarkedPost/${
        this.props.match.params.user
      }/${this.state.counter}`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          posts: this.state.posts.concat(data.posts)
        });
        if (data.posts.length > 0) {
          this.setState({
            requestSent: false,
            counter: this.state.counter + 10
          });
        }
      });
  }

  toggleInterest = interest => e => {
    fetch(`${this.props.apiROOT}interest/addInterest/${interest}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          //This is a check to prevent the component from using the
          //data in the Store object
          Store.userChange = true;

          document.querySelector("#user-" + interest).classList.toggle("green");
          document.querySelector(".user-menu-interest-count span").innerHTML =
            data.interestCount;
        }
      });
  };

  //This function can only be called by the follow button in the user
  //detail section
  toggleFollow = userID => e => {
    const userFollowBtn = document.querySelector(".user-follow-btn");
    fetch(`${this.props.apiROOT}follow/follow/${userID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "followed") {
          userFollowBtn.innerHTML = "Following";
        } else {
          userFollowBtn.innerHTML = "Follow";
        }

        //This is a check to prevent the component from using the
        //data in the Store object
        Store.userChange = true;

        document.querySelector(".user-follower-count span").innerHTML =
          data.followerCount;
      });
  };

  toggleFollowFromList = userID => e => {
    const userFollowList = document.querySelector("#follow-" + userID);
    fetch(`${this.props.apiROOT}follow/follow/${userID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "followed") {
          userFollowList.innerHTML = "Following";
        } else {
          userFollowList.innerHTML = "Follow";
        }

        //This is a check to prevent the component from using the
        //data in the Store object
        Store.userChange = true;

        //Get the id of the user whose profile page is being viewed
        let viewedProfileUserIDAry = this.props.match.params.user.split("-");
        let viewedProfileUserID =
          viewedProfileUserIDAry[viewedProfileUserIDAry.length - 1];

        //if the user whose profile is being viewed is thesame as the logged in user
        //update the count
        if (parseInt(viewedProfileUserID) === this.props.isLoggedInUserID) {
          document.querySelector(".user-following-count span").innerHTML =
            data.followerCount;
        }
      });
  };

  componentWillUpdate(nextProp) {
    if (
      this.props.match.params.user !== nextProp.match.params.user ||
      this.props.match.params.suser !== nextProp.match.params.suser
    ) {
      const { user, suser } = nextProp.match.params;

      let urlAry = nextProp.match.params.user.split("-");
      let userID = urlAry[urlAry.length - 1];
      userID = "user" + userID;

      if (userID in Store) {
        this.setState({
          user: Store[userID]["user"],
          username: Store[userID]["username"],
          bookmarkCount: Store[userID]["bookmarkCount"],
          postCount: Store[userID]["postCount"],
          followerCount: Store[userID]["followerCount"],
          followingCount: Store[userID]["followingCount"],
          interestCount: Store[userID]["interestCount"],
          scrollTo: Store[userID]["scrollTo"]
        });
      }

      if (!(userID in Store) || Store.userChange === true) {
        fetch(`${this.props.apiROOT}user/setUserDetails/${user}`)
          .then(res => res.json())
          .then(data => {
            this.setState({
              user: data.user,
              username: data.user.username,
              bookmarkCount: data.bookmarkCount,
              postCount: data.postCount,
              followerCount: data.followerCount,
              followingCount: data.followingCount,
              interestCount: data.interestCount
            });
          });
      }

      fetch(`${this.props.apiROOT}user/${user}/${suser}`)
        .then(res => res.json())
        .then(data => {
          this.setState({
            cmp: data.cmp,
            posts: data.posts,
            interests: data.interests,
            followings: data.followings,
            followers: data.followers,
            followingLite: data.followingLite,
            isFollowing: data.isFollowing
          });
        });
    }
  }

  render() {
    return (
      <section className="user-page-column">
        <div className="user-details-section">
          {this.state.user.profile_image === "" && (
            <div
              className="user-profile-image img"
              style={{
                backgroundImage:
                  "url(" +
                  this.props.root +
                  "public/images/profile_pic/avatar.jpg)"
              }}
            >
              <img className="img-decoy" alt={this.state.username} />
            </div>
          )}

          {this.state.user.profile_image !== "" && (
            <div
              className="user-profile-image img"
              style={{
                backgroundImage:
                  "url(" +
                  this.props.root +
                  "public/images/profile_pic/" +
                  this.state.user.profile_image +
                  ")"
              }}
            >
              <img className="img-decoy" alt={this.state.username} />
            </div>
          )}

          <div className="user-username-div">
            <p className="user-username">{this.state.username}</p>
            {parseInt(this.state.user.user_id) !==
              this.props.isLoggedInUserID && (
              <button
                className="btn user-follow-btn"
                onClick={this.toggleFollow(this.state.user.user_id)}
              >
                {this.state.isFollowing ? "Following" : "Follow"}
              </button>
            )}
          </div>

          {parseInt(this.state.user.user_id) ===
            this.props.isLoggedInUserID && (
            <Link to="/user/editProfile">
              <button className="user-edit-button btn">Edit</button>
            </Link>
          )}
          <div className="clear-float" />
        </div>

        <div className="user-menus">
          {parseInt(this.state.user.user_id) ===
            this.props.isLoggedInUserID && (
            <Link
              to={`/user/${this.state.username.replace(/ /g, "-")}-${parseInt(
                this.state.user.user_id
              )}/interest`}
            >
              <button className="user-menu-interest-count">
                Interests <span>{this.state.interestCount}</span>
              </button>
            </Link>
          )}
          <Link
            to={`/user/${this.state.username.replace(/ /g, "-")}-${parseInt(
              this.state.user.user_id
            )}/`}
          >
            <button>
              Posts <span>{this.state.postCount}</span>
            </button>
          </Link>

          <Link
            to={`/user/${this.state.username.replace(/ /g, "-")}-${parseInt(
              this.state.user.user_id
            )}/follower`}
          >
            <button className="user-follower-count">
              Followers <span>{this.state.followerCount}</span>
            </button>
          </Link>

          <Link
            to={`/user/${this.state.username.replace(/ /g, "-")}-${parseInt(
              this.state.user.user_id
            )}/following`}
          >
            <button className="user-following-count">
              Following <span>{this.state.followingCount}</span>
            </button>
          </Link>

          {parseInt(this.state.user.user_id) ===
            this.props.isLoggedInUserID && (
            <Link
              to={`/user/${this.state.username.replace(/ /g, "-")}-${parseInt(
                this.state.user.user_id
              )}/bookmark`}
            >
              <button className="user-bookmark-count">
                Bookmarks <span>{this.state.bookmarkCount}</span>
              </button>
            </Link>
          )}
        </div>

        {this.state.cmp === "" && (
          <div className="user-menu-posts">
            <Posts
              isLoggedIn={this.props.isLoggedIn}
              isLoggedInUserID={this.props.isLoggedInUserID}
              posts={this.state.posts}
              pageName="user"
              apiROOT={this.props.apiROOT}
              root={this.props.root}
              beforeListFetch={this.beforeListFetch}
            />
          </div>
        )}

        {this.state.cmp === "interest" && (
          <div className="user-menu-details">
            {this.state.interests.map(i => (
              <div className="user-profile-interest" key={i.interest}>
                <img
                  src={`${this.props.root}public/images/profile_pic/avatar.jpg`}
                  alt={i.interest}
                />
                <Link to={`/interest/${i.interest.replace(/ /g, "-")}`}>
                  <span>{i.interest}</span>
                </Link>
                <button
                  className="green"
                  onClick={this.toggleInterest(i.interest.replace(/ /g, "-"))}
                  id={`user-${i.interest.replace(/ /g, "-")}`}
                >
                  <MarkSVG />
                </button>
              </div>
            ))}
            <div className="clear-float" />
          </div>
        )}

        {this.state.cmp === "follower" && (
          <div className="user-menu-details">
            <Follower
              apiROOT={this.props.apiROOT}
              root={this.props.root}
              toggleFollow={this.toggleFollowFromList}
              followers={this.state.followers}
              followingLite={this.state.followingLite}
              isLoggedInUserID={this.props.isLoggedInUserID}
            />
          </div>
        )}

        {this.state.cmp === "following" && (
          <div className="user-menu-details">
            <Following
              apiROOT={this.props.apiROOT}
              root={this.props.root}
              toggleFollow={this.toggleFollowFromList}
              followings={this.state.followings}
              isLoggedInUserID={this.props.isLoggedInUserID}
            />
          </div>
        )}

        {this.state.cmp === "bookmark" && (
          <div className="user-menu-posts">
            <Posts
              isLoggedIn={this.props.isLoggedIn}
              posts={this.state.posts}
              pageName="user"
              apiROOT={this.props.apiROOT}
              root={this.props.root}
              beforeListFetch={this.beforeListFetch}
            />
          </div>
        )}

        <Route
          exact
          path="/user/editProfile"
          render={props => (
            <UserFormEditor
              {...props}
              apiROOT={this.props.apiROOT}
              root={this.props.root}
            />
          )}
        />
      </section>
    );
  }
}

export default User;
