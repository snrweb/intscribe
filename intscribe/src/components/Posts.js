import React, { Component } from "react";
import { Link } from "react-router-dom";
import { ReactComponent as CommentSVG } from "../images/svg/comment.svg";
import { ReactComponent as BookmarkSVG } from "../images/svg/bookmark.svg";
import { ReactComponent as UpvoteSVG } from "../images/svg/arrow-up.svg";
import { ReactComponent as DownvoteSVG } from "../images/svg/arrow-down.svg";
import TimeDiff from "../helpers/TimeDiff";
import CountSetter from "../helpers/CountSetter";

class Posts extends Component {
  constructor(props) {
    super(props);

    this.article = this.article.bind(this);
    this.poll = this.poll.bind(this);
    this.question = this.question.bind(this);
    this.renderPosts = this.renderPosts.bind(this);

    this.toggleBookmark = this.toggleBookmark.bind(this);
    this.deletePoll = this.deletePoll.bind(this);
    this.addVote = this.addVote.bind(this);

    this.handleOnScroll = this.handleOnScroll.bind(this);
  }

  componentDidMount() {
    window.addEventListener("scroll", this.handleOnScroll);
  }

  componentWillUnmount() {
    window.removeEventListener("scroll", this.handleOnScroll);
  }

  handleOnScroll() {
    const elemWrapper = document.querySelector("#post-lists");
    let scrollTop = window.pageYOffset;
    let scrollHeight = elemWrapper.scrollHeight;
    let clientHeight = 500;

    let scrollPosition = Math.ceil(scrollTop + clientHeight) >= scrollHeight;
    if (scrollPosition) {
      this.props.beforeListFetch();
    }
  }

  toggleBookmark = postID => e => {
    fetch(`${this.props.apiROOT}bookmark/add/${postID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          document
            .querySelector("#bookmark" + postID)
            .classList.toggle("green-fill");

          if (document.querySelector(".c1-bookmark-count span")) {
            document.querySelector(".c1-bookmark-count span").innerHTML =
              data.bookmarkCount;
          }

          if (document.querySelector(".user-bookmark-count span")) {
            document.querySelector(".user-bookmark-count span").innerHTML =
              data.bookmarkCount;
          }
        }
      });
  };

  deletePoll = postID => e => {
    if (window.confirm("Do you want to delete this poll?")) {
      fetch(`${this.props.apiROOT}post/delete/${postID}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === true) {
            document.querySelector("#post" + postID).style.display = "none";
          }
        });
    }
  };

  addVote = (postID, option) => e => {
    fetch(`${this.props.apiROOT}post/addVote/${postID}/${option}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          document.querySelector(
            `#c2-poll-option-active-${postID}-1`
          ).style.width = data.opOne + "%";
          document.querySelector(`#c2-poll-option-c-${postID}-1`).innerHTML =
            data.opOne + "%";

          document.querySelector(
            `#c2-poll-option-active-${postID}-2`
          ).style.width = data.opTwo + "%";
          document.querySelector(`#c2-poll-option-c-${postID}-2`).innerHTML =
            data.opTwo + "%";

          if (data.opThree !== 0) {
            document.querySelector(
              `#c2-poll-option-active-${postID}-3`
            ).style.width = data.opThree + "%";
            document.querySelector(`#c2-poll-option-c-${postID}-3`).innerHTML =
              data.opThree + "%";
          }

          if (data.opFour !== 0) {
            document.querySelector(
              `#c2-poll-option-active-${postID}-4`
            ).style.width = data.opFour + "%";
            document.querySelector(`#c2-poll-option-c-${postID}-4`).innerHTML =
              data.opFour + "%";
          }

          document.querySelector(`#c2-total-poll${postID} span`).innerHTML =
            data.sum;
        }
      });
  };

  article(p) {
    return (
      <div className="c2-posts" key={p.post_id}>
        <div className="c2-post">
          <div className="c2-post-head">
            {p.profile_image === "" ? (
              <div
                className="c2-poster-image img"
                style={{
                  backgroundImage:
                    "url(" +
                    this.props.root +
                    "public/images/profile_pic/avatar.jpg)"
                }}
              >
                <img className="img-decoy" alt={p.username} />
              </div>
            ) : (
              <div
                className="c2-poster-image img"
                style={{
                  backgroundImage:
                    "url(" +
                    this.props.root +
                    "public/images/profile_pic/" +
                    p.profile_image +
                    ")"
                }}
              >
                <img className="img-decoy" alt={p.username} />
              </div>
            )}

            <div className="c2-poster-profile">
              <Link to={`/user/${p.username.replace(/ /g, "-")}-${p.user_id}`}>
                <p className="c2-poster-username">{p.username}</p>
              </Link>
              <p className="c2-post-time">
                <TimeDiff date={p.created_at} />{" "}
              </p>
            </div>
            <Link to={`/interest/${p.post_int.replace(/ /g, "-")}`}>
              <p className="c2-interest-name">{p.post_int}</p>
            </Link>
            <div className="clear-float" />
          </div>
          <div className="c2-post-in">
            <Link
              to={`/post/${p.post_title
                .replace(/ /g, "-")
                .replace(/\?/g, "")}-${p.post_id}`}
            >
              <p className="c2-post-title">{p.post_title}</p>
              <p
                className="c2-post-body"
                dangerouslySetInnerHTML={{
                  __html:
                    p.main_post.length > 200
                      ? p.main_post.substring(
                          0,
                          p.main_post.lastIndexOf(" ", 200)
                        )
                      : p.main_post
                }}
              >
                {}
              </p>
            </Link>
          </div>
          <div className="c2-post-counts">
            <p className="c2-comment-count" style={{ marginTop: -5 + "px" }}>
              <CommentSVG />
              <CountSetter count={p.post_comments} />
            </p>

            <p className="c2-upvote-count">
              {p.status === "1" && (
                <span className="c2-upvote-status">upvoted</span>
              )}

              {p.status === "-1" && (
                <span className="c2-upvote-status">downvoted</span>
              )}
              <span
                className={
                  this.props.isLoggedIn && p.status > 0 ? "upvote" : "neutral"
                }
              >
                <UpvoteSVG />
              </span>

              <CountSetter count={p.post_promotes} />

              <span
                className={
                  this.props.isLoggedIn && p.status < 0 ? "downvote" : "neutral"
                }
              >
                <DownvoteSVG />
              </span>
            </p>

            <span
              onClick={this.toggleBookmark(p.post_id)}
              id={`bookmark${p.post_id}`}
              className={`c2-bookmark ${
                this.props.isLoggedIn && parseInt(p.bookmark_id) > 0
                  ? "upvote"
                  : "neutral"
              }`}
            >
              <BookmarkSVG />
            </span>
          </div>
        </div>
      </div>
    );
  }

  question(p) {
    return (
      <div className="c2-posts" key={p.post_id}>
        <div className="c2-post">
          <div
            className="c2-post-head"
            style={{
              borderBottom: 1 + "px solid #a09f9f",
              color: "#3b3b3b"
            }}
          >
            <p
              className="pull-left"
              style={{
                marginTop: -4 + "px",
                marginLeft: 0,
                marginBottom: 4 + "px"
              }}
            >
              Question
            </p>
            <Link to={`/interest/${p.post_int.replace(/ /g, "-")}`}>
              <p
                className="pull-right"
                style={{
                  marginTop: -4 + "px",
                  marginLeft: 0,
                  marginBottom: 4 + "px"
                }}
              >
                {p.post_int}
              </p>
            </Link>
            <div className="clear-float" />
          </div>
          <div className="c2-post-in">
            <Link
              to={`/post/${p.post_title
                .replace(/ /g, "-")
                .replace(/\?/g, "")}-${p.post_id}`}
            >
              <p className="c2-post-title">{p.post_title}</p>
            </Link>
            {p.question_link !== "" && (
              <a
                href={p.question_link}
                target="_blank"
                rel="noopener noreferrer"
                style={{
                  fontSize: 0.9 + "em",
                  display: "block",
                  color: "grey",
                  marginTop: -7 + "px"
                }}
              >
                Ref link: {p.question_link}
              </a>
            )}
          </div>
          <div className="c2-post-counts">
            <p className="c2-comment-count" style={{ marginTop: -5 + "px" }}>
              <CommentSVG />
              <CountSetter count={p.post_comments} />
            </p>
            <p className="c2-upvote-count">
              {p.status === "1" && (
                <span className="c2-upvote-status">upvoted</span>
              )}

              {p.status === "-1" && (
                <span className="c2-upvote-status">downvoted</span>
              )}
              <span
                className={
                  this.props.isLoggedIn && p.status > 0 ? "upvote" : "neutral"
                }
              >
                <UpvoteSVG />
              </span>

              <CountSetter count={p.post_promotes} />

              <span
                className={
                  this.props.isLoggedIn && p.status < 0 ? "downvote" : "neutral"
                }
              >
                <DownvoteSVG />
              </span>
            </p>

            <span
              onClick={this.toggleBookmark(p.post_id)}
              id={`bookmark${p.post_id}`}
              className={`c2-bookmark ${
                this.props.isLoggedIn && parseInt(p.bookmark_id) > 0
                  ? "upvote"
                  : "neutral"
              }`}
            >
              <BookmarkSVG />
            </span>
          </div>
        </div>
      </div>
    );
  }

  poll(p) {
    return (
      <div className="c2-posts" key={p.post_id} id={`post${p.post_id}`}>
        <div className="c2-post">
          <div className="c2-post-head">
            {p.profile_image === "" ? (
              <div
                className="c2-poster-image img"
                style={{
                  backgroundImage:
                    "url(" +
                    this.props.root +
                    "public/images/profile_pic/avatar.jpg)"
                }}
              >
                <img className="img-decoy" alt={p.username} />
              </div>
            ) : (
              <div
                className="c2-poster-image img"
                style={{
                  backgroundImage:
                    "url(" +
                    this.props.root +
                    "public/images/profile_pic/" +
                    p.profile_image +
                    ")"
                }}
              >
                <img className="img-decoy" alt={p.username} />
              </div>
            )}

            <div className="c2-poster-profile">
              <Link to={`/user/${p.username.replace(/ /g, "-")}-${p.user_id}`}>
                <p className="c2-poster-username">{p.username}</p>
              </Link>
              <p className="c2-post-time">
                <TimeDiff date={p.created_at} />{" "}
              </p>
            </div>
            <Link to={`/interest/${p.post_int.replace(/ /g, "-")}`}>
              <p className="c2-interest-name">{p.post_int}</p>
            </Link>
            <div className="clear-float" />
          </div>

          <div className="c2-post-in">
            <p className="c2-post-title">{p.post_title}</p>
            <div className="c2-poll-options">
              <div className="c2-poll-option">
                <div
                  className="c2-poll-option-active"
                  id={`c2-poll-option-active-${p.post_id}-1`}
                  style={{
                    width:
                      Math.round(
                        (parseInt(p.option_one_count) /
                          (parseInt(p.option_one_count) +
                            parseInt(p.option_two_count) +
                            parseInt(p.option_three_count) +
                            parseInt(p.option_four_count))) *
                          100
                      ) + "%"
                  }}
                />
                <p
                  className="c2-poll-option-c"
                  id={`c2-poll-option-c-${p.post_id}-1`}
                >
                  {parseInt(p.option_one_count) +
                    parseInt(p.option_two_count) +
                    parseInt(p.option_three_count) +
                    parseInt(p.option_four_count) !==
                  0
                    ? Math.round(
                        (parseInt(p.option_one_count) /
                          (parseInt(p.option_one_count) +
                            parseInt(p.option_two_count) +
                            parseInt(p.option_three_count) +
                            parseInt(p.option_four_count))) *
                          100
                      )
                    : 0}
                  %
                </p>
                <p className="c2-poll-option-o">{p.option_one}</p>
                <div
                  className="c2-poll-option-click"
                  onClick={this.addVote(p.post_id, 1)}
                />
              </div>

              <div className="c2-poll-option">
                <div
                  className="c2-poll-option-active"
                  id={`c2-poll-option-active-${p.post_id}-2`}
                  style={{
                    width:
                      Math.round(
                        (parseInt(p.option_two_count) /
                          (parseInt(p.option_one_count) +
                            parseInt(p.option_two_count) +
                            parseInt(p.option_three_count) +
                            parseInt(p.option_four_count))) *
                          100
                      ) + "%"
                  }}
                />
                <p
                  className="c2-poll-option-c"
                  id={`c2-poll-option-c-${p.post_id}-2`}
                >
                  {parseInt(p.option_one_count) +
                    parseInt(p.option_two_count) +
                    parseInt(p.option_three_count) +
                    parseInt(p.option_four_count) !==
                  0
                    ? Math.round(
                        (parseInt(p.option_two_count) /
                          (parseInt(p.option_one_count) +
                            parseInt(p.option_two_count) +
                            parseInt(p.option_three_count) +
                            parseInt(p.option_four_count))) *
                          100
                      )
                    : 0}
                  %
                </p>
                <p className="c2-poll-option-o">{p.option_two}</p>
                <div
                  className="c2-poll-option-click"
                  onClick={this.addVote(p.post_id, 2)}
                />
              </div>

              {p.option_three !== "" && (
                <div className="c2-poll-option">
                  <div
                    className="c2-poll-option-active"
                    id={`c2-poll-option-active-${p.post_id}-3`}
                    style={{
                      width:
                        Math.round(
                          (parseInt(p.option_three_count) /
                            (parseInt(p.option_one_count) +
                              parseInt(p.option_two_count) +
                              parseInt(p.option_three_count) +
                              parseInt(p.option_four_count))) *
                            100
                        ) + "%"
                    }}
                  />
                  <p
                    className="c2-poll-option-c"
                    id={`c2-poll-option-c-${p.post_id}-3`}
                  >
                    {parseInt(p.option_one_count) +
                      parseInt(p.option_two_count) +
                      parseInt(p.option_three_count) +
                      parseInt(p.option_four_count) !==
                    0
                      ? Math.round(
                          (parseInt(p.option_three_count) /
                            (parseInt(p.option_one_count) +
                              parseInt(p.option_two_count) +
                              parseInt(p.option_three_count) +
                              parseInt(p.option_four_count))) *
                            100
                        )
                      : 0}
                    %
                  </p>
                  <p className="c2-poll-option-o">{p.option_three}</p>
                  <div
                    className="c2-poll-option-click"
                    onClick={this.addVote(p.post_id, 3)}
                  />
                </div>
              )}

              {p.option_four !== "" && (
                <div className="c2-poll-option">
                  <div
                    className="c2-poll-option-active"
                    id={`c2-poll-option-active-${p.post_id}-4`}
                    style={{
                      width:
                        Math.round(
                          (parseInt(p.option_four_count) /
                            (parseInt(p.option_one_count) +
                              parseInt(p.option_two_count) +
                              parseInt(p.option_three_count) +
                              parseInt(p.option_four_count))) *
                            100
                        ) + "%"
                    }}
                  />
                  <p
                    className="c2-poll-option-c"
                    id={`c2-poll-option-c-${p.post_id}-4`}
                  >
                    {parseInt(p.option_one_count) +
                      parseInt(p.option_two_count) +
                      parseInt(p.option_three_count) +
                      parseInt(p.option_four_count) !==
                    0
                      ? Math.round(
                          (parseInt(p.option_four_count) /
                            (parseInt(p.option_one_count) +
                              parseInt(p.option_two_count) +
                              parseInt(p.option_three_count) +
                              parseInt(p.option_four_count))) *
                            100
                        )
                      : 0}
                    %
                  </p>
                  <p className="c2-poll-option-o">{p.option_four}</p>
                  <div
                    className="c2-poll-option-click"
                    onClick={this.addVote(p.post_id, 4)}
                  />
                </div>
              )}
            </div>
          </div>
          <div className="c2-post-counts">
            <p className="c2-comment-count" id={`c2-total-poll${p.post_id}`}>
              Total votes{" "}
              <span>
                {parseInt(p.option_one_count) +
                  parseInt(p.option_two_count) +
                  parseInt(p.option_three_count) +
                  parseInt(p.option_four_count)}
              </span>
            </p>
            <p className="c2-upvote-count">
              {new Date() > new Date(p.duration) ? "Closed" : "Ongoing"}
            </p>
            {parseInt(p.user_id) === parseInt(this.props.isLoggedInUserID) && (
              <p
                className="c2-post-delete"
                onClick={this.deletePoll(p.post_id)}
              >
                Delete
              </p>
            )}

            <span
              onClick={this.toggleBookmark(p.post_id)}
              id={`bookmark${p.post_id}`}
              className={`c2-bookmark ${
                this.props.isLoggedIn && parseInt(p.bookmark_id) > 0
                  ? "upvote"
                  : "neutral"
              }`}
            >
              <BookmarkSVG />
            </span>
          </div>
        </div>
      </div>
    );
  }

  renderPosts() {
    return this.props.posts.map(p => {
      if (p.post_type === "Article") {
        return this.article(p);
      } else if (p.post_type === "Question") {
        return this.question(p);
      } else {
        return this.poll(p);
      }
    });
  }

  render() {
    return (
      <React.Fragment>
        <div id="post-lists">{this.renderPosts()}</div>
      </React.Fragment>
    );
  }
}

export default Posts;
