import React, { Component } from "react";
import { Link } from "react-router-dom";
import { ReactComponent as BookmarkSVG } from "../images/svg/bookmark.svg";
import { ReactComponent as UpvoteSVG } from "../images/svg/arrow-up.svg";
import { ReactComponent as DownvoteSVG } from "../images/svg/arrow-down.svg";
import { ReactComponent as FollowSVG } from "../images/svg/follow.svg";
import { ReactComponent as DeleteSVG } from "../images/svg/delete.svg";
import { ReactComponent as EditSVG } from "../images/svg/edit.svg";
import { ReactComponent as ReplySVG } from "../images/svg/reply.svg";
import { ReactComponent as CloseSVG } from "../images/svg/close.svg";
import TimeDiff from "../helpers/TimeDiff";
import CountSetter from "../helpers/CountSetter";
import ColumnOne from "../components/ColumOne";
import ArticleEditor from "../editors/ArticleEditor";
import CommentEditor from "../editors/CommentEditor";
import SubCommentEditor from "../editors/SubCommentEditor";

//An object to hold the state properties and values of this component
import { Store } from "../store/Store";

class MainPost extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoggedIn: true,
      isLoggedInUserID: 1,
      post: {},
      comments: [],
      commentID: 0,
      comment_userID: 0,
      subComments: [],
      renderCount: 0,
      commentSetter: "insert",
      subCommentID: 0,
      subCommentSetter: "insert",
      requestSent: false,
      counter: 2,
      hasFetchedAllPost: false,
      scrollTo: 0
    };

    this.toggleArticleEditor = this.toggleArticleEditor.bind(this);
    this.toggleCommentEditor = this.toggleCommentEditor.bind(this);
    this.toggleSubCommentEditor = this.toggleSubCommentEditor.bind(this);
    this.setSubCommentID = this.setSubCommentID.bind(this);
    this.handleOnScroll = this.handleOnScroll.bind(this);
    this.beforeListFetch = this.beforeListFetch.bind(this);
    this.fetchCommentList = this.fetchCommentList.bind(this);
  }

  componentDidMount() {
    window.addEventListener("scroll", this.handleOnScroll);

    let urlAry = this.props.match.params.title.split("-");
    let postID = urlAry[urlAry.length - 1];

    if (postID in Store) {
      this.setState({
        isLoggedIn: Store[postID]["isLoggedIn"],
        isLoggedInUserID: Store[postID]["isLoggedInUserID"],
        post: Store[postID]["post"],
        comments: Store[postID]["comments"],
        commentID: Store[postID]["commentID"],
        comment_userID: Store[postID]["comment_userID"],
        subComments: Store[postID]["subComments"],
        renderCount: Store[postID]["renderCount"],
        commentSetter: Store[postID]["commentSetter"],
        subCommentID: Store[postID]["subCommentID"],
        subCommentSetter: Store[postID]["subCommentSetter"],
        requestSent: Store[postID]["requestSent"],
        counter: Store[postID]["counter"],
        hasFetchedAllPost: Store[postID]["hasFetchedAllPost"],
        scrollTo: Store[postID]["scrollTo"]
      });
    } else {
      fetch(`${this.props.apiROOT}post/${this.props.match.params.title}`)
        .then(res => res.json())
        .then(data => {
          this.setState({
            post: data.post,
            comments: data.comments,
            subComments: data.subComments
          });
        });
    }
  }

  componentWillUnmount() {
    let urlAry = this.props.match.params.title.split("-");
    let postID = urlAry[urlAry.length - 1];
    Store[postID] = this.state;
    window.removeEventListener("scroll", this.handleOnScroll);
  }

  handleOnScroll() {
    const elemWrapper = document.querySelector(".post-page-column");
    let scrollTop = window.pageYOffset;
    let scrollHeight = elemWrapper.scrollHeight;
    let clientHeight = 500;

    this.setState({ scrollTo: scrollTop });

    let scrollPosition = Math.ceil(scrollTop + clientHeight) >= scrollHeight;
    if (scrollPosition) {
      this.beforeListFetch();
    }
  }

  beforeListFetch() {
    if (this.state.requestSent) return;

    setTimeout(this.fetchCommentList, 2000);
    this.setState({ requestSent: true });
  }

  fetchCommentList() {
    if (this.state.hasFetchedAllPost) return;
    fetch(
      `${this.props.apiROOT}post/postComments/${this.state.post.post_id}/${
        this.state.counter
      }`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          comments: this.state.comments.concat(data.comments)
        });

        if (data.comments.length < 2) {
          this.setState({
            requestSent: false,
            counter: this.state.counter + 2,
            hasFetchedAllPost: true
          });
        } else {
          this.setState({
            requestSent: false,
            counter: this.state.counter + 2
          });
        }
      });
  }

  toggleArticleEditor() {
    let editor = document.getElementById("article_editor_frame");
    editor.contentWindow.document.designMode = "On";

    let elem = document.querySelector(".article-editor-wrapper");
    if (elem.classList.contains("hide")) {
      editor.contentWindow.document.body.innerHTML = this.state.post.main_post;
      elem.classList.remove("hide");
      elem.classList.add("visible");
    } else {
      elem.classList.add("hide");
      elem.classList.remove("visible");
    }
  }

  toggleCommentEditor() {
    let editor = document.getElementById("comment_editor_frame");
    editor.contentWindow.document.designMode = "On";

    let elem = document.querySelector(".comment-editor-wrapper");
    if (elem.classList.contains("hide")) {
      elem.classList.remove("hide");
      elem.classList.add("visible");
    } else {
      elem.classList.add("hide");
      elem.classList.remove("visible");
    }
  }

  setCommentID = comment_id => e => {
    this.setState({ commentID: comment_id, commentSetter: "update" });
    fetch(`${this.props.apiROOT}comment/edit/${comment_id}`)
      .then(res => res.json())
      .then(data => {
        let editor = document.querySelector("#comment_editor_frame");
        editor.contentWindow.document.body.innerHTML = data.comment;
      });
    this.toggleCommentEditor();
  };

  toggleSubCommentEditor(e) {
    let comment_id = e.target.getAttribute("data-cid");
    let comment_user_id = e.target.getAttribute("data-user");
    this.setState({ commentID: comment_id, comment_userID: comment_user_id });
    let editor = document.getElementById("sub_comment_editor_frame");
    editor.contentWindow.document.designMode = "On";

    if (e.target.hasAttribute("data-scid")) {
      let sub_comment_id = e.target.getAttribute("data-scid");
      fetch(`${this.props.apiROOT}subComment/edit/${sub_comment_id}`)
        .then(res => res.json())
        .then(data => {
          let editor = document.querySelector("#sub_comment_editor_frame");
          editor.contentWindow.document.body.innerHTML = data.subComment;
        });
    }

    let elem = document.querySelector(".sub-comment-editor-wrapper");
    if (elem.classList.contains("hide")) {
      elem.classList.remove("hide");
      elem.classList.add("visible");
    } else {
      elem.classList.add("hide");
      elem.classList.remove("visible");
    }
  }

  setSubCommentID(e) {
    let sub_comment_id = e.target.getAttribute("data-scid");
    this.setState({ subCommentID: sub_comment_id, subCommentSetter: "update" });
    this.toggleSubCommentEditor(e);
  }

  toggleFollow = userID => e => {
    let elem = document.querySelector(".pg-follow-poster-btn");
    fetch(`${this.props.apiROOT}follow/follow/${userID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "followed") {
          elem.classList.remove("unfollowed");
          elem.classList.add("followed");
        } else {
          elem.classList.remove("followed");
          elem.classList.add("unfollowed");
        }
        document.querySelector(".c1-user-following-count span").innerHTML =
          data.followerCount;
      });
  };

  toggleBookmark = postID => e => {
    let elem = document.querySelector("#bookmark" + postID);

    fetch(`${this.props.apiROOT}bookmark/add/${postID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          elem.classList.toggle("bookmarked");
        }

        if (document.querySelector(".c1-bookmark-count span")) {
          document.querySelector(".c1-bookmark-count span").innerHTML =
            data.bookmarkCount;
        }
      });
  };

  postDelete = postID => e => {
    if (window.confirm("Are you sure you want to delete post?")) {
      fetch(`${this.props.apiROOT}post/delete/${postID}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === true) {
            window.history.back();
          }
        });
    }
  };

  postPromote = postID => e => {
    let elem = document.querySelector(".pg-p-upvote");
    elem.classList.remove("neutral");

    fetch(`${this.props.apiROOT}post/promote/${postID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          elem.classList.toggle("upvote");
          document.querySelector(".pg-p-downvote").classList.remove("downvote");
          document.querySelector(".pg-p-upvote-sum").innerHTML = data.sum;
        }
      });
  };

  postDemote = postID => e => {
    let elem = document.querySelector(".pg-p-downvote");
    elem.classList.remove("neutral");

    fetch(`${this.props.apiROOT}post/demote/${postID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          elem.classList.toggle("downvote");
          document.querySelector(".pg-p-upvote").classList.remove("upvote");
          document.querySelector(".pg-p-upvote-sum").innerHTML = data.sum;
        }
      });
  };

  commentPromote = (postID, commentID) => e => {
    let elem = document.querySelector(".pg-c-upvote-" + commentID);
    elem.classList.remove("neutral");
    fetch(`${this.props.apiROOT}comment/promote/${postID}/${commentID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          elem.classList.toggle("upvote");
          document
            .querySelector(".pg-c-downvote-" + commentID)
            .classList.remove("downvote");
          document.querySelector(".pg-c-upvote-sum-" + commentID).innerHTML =
            data.sum;
        }
      });
  };

  commentDemote = (postID, commentID) => e => {
    let elem = document.querySelector(".pg-c-downvote-" + commentID);
    elem.classList.remove("neutral");

    fetch(`${this.props.apiROOT}comment/demote/${postID}/${commentID}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          elem.classList.toggle("downvote");
          document
            .querySelector(".pg-c-upvote-" + commentID)
            .classList.remove("upvote");
          document.querySelector(".pg-c-upvote-sum-" + commentID).innerHTML =
            data.sum;
        }
      });
  };

  commentDelete = (postID, commentID) => e => {
    if (window.confirm("Are you sure you want to delete comment?")) {
      fetch(`${this.props.apiROOT}comment/delete/${postID}/${commentID}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === true) {
            document.querySelector("#pg-comment-" + commentID).style.display =
              "none";
          }
        });
    }
  };

  subCommentDelete = (postID, scommentID) => e => {
    if (window.confirm("Are you sure you want to delete comment?")) {
      fetch(`${this.props.apiROOT}subComment/delete/${postID}/${scommentID}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === true) {
            document.querySelector(
              ".pg-sub-comment-" + scommentID
            ).style.display = "none";
          }
        });
    }
  };

  render() {
    return (
      <React.Fragment>
        <ColumnOne />
        <div className="post-page-column">
          {/* post */}
          <div className="pg-post">
            {this.state.post.post_type === "Article" && (
              <React.Fragment>
                <p className="pg-post-title">{this.state.post.post_title} </p>
                <div className="pg-post-head">
                  {this.state.post.profile_image === "" ? (
                    <div
                      className="pg-poster-image img"
                      style={{
                        backgroundImage:
                          "url(" +
                          this.props.root +
                          "public/images/profile_pic/avatar.jpg)"
                      }}
                    >
                      <img
                        className="img-decoy"
                        alt={this.state.post.username}
                      />
                    </div>
                  ) : (
                    <div
                      className="pg-poster-image img"
                      style={{
                        backgroundImage:
                          "url(" +
                          this.props.root +
                          "public/images/profile_pic/" +
                          this.state.post.profile_image +
                          ")"
                      }}
                    >
                      <img
                        className="img-decoy"
                        alt={this.state.post.username}
                      />
                    </div>
                  )}

                  <div className="pg-poster-profile">
                    <Link
                      to={`/user/${this.state.post.username.replace(
                        / /g,
                        "-"
                      )}-${this.state.post.user_id}`}
                    >
                      <p className="pg-poster-username">
                        {this.state.post.username}
                      </p>
                    </Link>
                    <p className="pg-post-time">
                      <TimeDiff date={this.state.post.created_at} />
                    </p>
                  </div>

                  <Link
                    to={`/interest/${this.state.post.post_int.replace(
                      / /g,
                      "-"
                    )}`}
                  >
                    <p className="pg-interest-name">
                      {this.state.post.post_int}
                    </p>
                  </Link>

                  {this.state.isLoggedInUserID !==
                    parseInt(this.state.post.user_id) && (
                    <p
                      className={`pg-follow-poster-btn ${
                        this.state.post.follow_id > 0
                          ? "followed"
                          : "unfollowed"
                      }`}
                      style={{ paddingBottom: 0 + "px" }}
                      onClick={this.toggleFollow(this.state.post.user_id)}
                    >
                      <FollowSVG />
                    </p>
                  )}
                  <div className="clear-float" />
                </div>
                <div className="pg-post-body">
                  <p
                    className="pg-post-in"
                    dangerouslySetInnerHTML={{
                      __html: this.state.post.main_post
                    }}
                  />
                </div>
              </React.Fragment>
            )}

            {this.state.post.post_type === "Question" && (
              <React.Fragment>
                <p className="pg-post-title">
                  {this.state.post.post_title}{" "}
                  <a
                    href={this.state.post.question_link}
                    target="_blank"
                    rel="noopener noreferrer"
                    style={{
                      fontSize: 0.5 + "em",
                      color: "grey"
                    }}
                  >
                    Ref link: {this.state.post.question_link}
                  </a>
                </p>

                <span className="pg-post-q-span">
                  Asked by {this.state.post.username} -{" "}
                  <TimeDiff date={this.state.post.created_at} />
                </span>
              </React.Fragment>
            )}

            <div className="pg-post-ft-2">
              {parseInt(this.state.post.user_id) ===
                this.state.isLoggedInUserID && (
                <p
                  className="pg-p-comment-delete"
                  onClick={this.postDelete(this.state.post.post_id)}
                >
                  <DeleteSVG /> <span>Delete</span>
                </p>
              )}

              <Link to={`/post/report/Post/${this.state.post.post_id}`}>
                <p className="pg-p-comment-report">Report</p>
              </Link>

              {parseInt(this.state.post.user_id) ===
                this.state.isLoggedInUserID &&
                this.state.post.post_type === "Article" && (
                  <p
                    className="pg-p-comment-edit"
                    onClick={this.toggleArticleEditor}
                  >
                    <EditSVG /> <span>Edit</span>
                  </p>
                )}
            </div>
            <div className="clear-float" />

            <div className="pg-post-ft">
              <p
                className="pg-p-comment-add"
                onClick={this.toggleCommentEditor}
              >
                Add comment
              </p>

              <p className={`pg-p-upvote-count `}>
                <span
                  className={`pg-p-upvote ${
                    this.state.isLoggedIn &&
                    parseInt(this.state.post.status) > 0
                      ? "upvote"
                      : "neutral"
                  }`}
                  onClick={this.postPromote(this.state.post.post_id)}
                >
                  <UpvoteSVG />
                </span>

                <span className="pg-p-upvote-sum">
                  {this.state.post.post_promotes !== undefined && (
                    <CountSetter count={this.state.post.post_promotes} />
                  )}
                </span>

                <span
                  className={`pg-p-downvote ${
                    this.state.isLoggedIn &&
                    parseInt(this.state.post.status) < 0
                      ? "downvote"
                      : "neutral"
                  }`}
                  onClick={this.postDemote(this.state.post.post_id)}
                >
                  <DownvoteSVG />
                </span>
              </p>

              <p
                id={`bookmark${this.state.post.post_id}`}
                className={`pg-p-bookmark ${
                  this.state.isLoggedIn &&
                  parseInt(this.state.post.bookmark_id) > 0
                    ? "bookmarked"
                    : "neutral"
                }`}
                onClick={this.toggleBookmark(this.state.post.post_id)}
              >
                <BookmarkSVG />
              </p>
            </div>
          </div>

          {/* comment */}
          {this.state.comments.map(c => (
            <div
              className="pg-comment"
              id={`pg-comment-${c.comment_id}`}
              key={c.comment_id}
            >
              <div className="pg-comment-head">
                {c.profile_image === "" ? (
                  <div
                    className="pg-commenter-image img"
                    style={{
                      backgroundImage:
                        "url(" +
                        this.props.root +
                        "public/images/profile_pic/avatar.jpg)"
                    }}
                  >
                    <img className="img-decoy" alt={c.username} />
                  </div>
                ) : (
                  <div
                    className="pg-commenter-image img"
                    style={{
                      backgroundImage:
                        "url(" +
                        this.props.root +
                        "public/images/profile_pic/" +
                        c.profile_image +
                        ")"
                    }}
                  >
                    <img className="img-decoy" alt={c.username} />
                  </div>
                )}

                <div className="pg-commenter-profile">
                  <Link
                    to={`/user/${c.username.replace(/ /g, "-")}-${c.user_id}`}
                  >
                    <p className="pg-commenter-username">{c.username}</p>
                  </Link>
                  <p className="pg-comment-time">
                    <TimeDiff date={c.created_at} />
                  </p>
                </div>
                <div className="clear-float" />
              </div>
              <div className="pg-comment-body">
                <p
                  className={`pg-comment-in pg-comment-in-${c.comment_id}`}
                  dangerouslySetInnerHTML={{
                    __html: c.comment
                  }}
                />
              </div>

              <div className="pg-comment-ft-2">
                {parseInt(c.user_id) === this.state.isLoggedInUserID && (
                  <p className="pg-c-comment-delete">
                    <DeleteSVG />{" "}
                    <span
                      onClick={this.commentDelete(
                        this.state.post.post_id,
                        c.comment_id
                      )}
                    >
                      Delete
                    </span>
                  </p>
                )}

                {parseInt(c.user_id) === this.state.isLoggedInUserID && (
                  <p
                    className="pg-c-comment-edit"
                    onClick={this.setCommentID(c.comment_id)}
                  >
                    <EditSVG /> <span>Edit</span>
                  </p>
                )}
                <div className="clear-float" />
              </div>

              <div className="pg-comment-ft">
                <p className="pg-c-comment-reply">
                  <ReplySVG />
                  <span
                    data-user={c.user_id}
                    data-cid={c.comment_id}
                    onClick={this.toggleSubCommentEditor}
                  >
                    Reply
                  </span>
                </p>

                <p className="pg-c-upvote-count">
                  <span
                    className={`pg-c-upvote-${c.comment_id} ${
                      this.state.isLoggedIn && c.status > 0
                        ? "upvote"
                        : "neutral"
                    }`}
                    onClick={this.commentPromote(
                      this.state.post.post_id,
                      c.comment_id
                    )}
                  >
                    <UpvoteSVG />
                  </span>

                  <span className={`pg-c-upvote-sum-${c.comment_id}`}>
                    <CountSetter count={c.comment_promotes} />
                  </span>

                  <span
                    className={`pg-c-downvote-${c.comment_id} ${
                      this.state.isLoggedIn && c.status < 0
                        ? "downvote"
                        : "neutral"
                    }`}
                    onClick={this.commentDemote(
                      this.state.post.post_id,
                      c.comment_id
                    )}
                  >
                    <DownvoteSVG />
                  </span>
                </p>

                <Link
                  to={`/post/report/Comment/${this.state.post.post_id}/${
                    c.comment_id
                  }`}
                >
                  <p className="pg-c-comment-report">Report</p>
                </Link>
              </div>

              {/* sub-comment */}
              {this.state.subComments.map(
                sc =>
                  sc.comment_id === c.comment_id && (
                    <div
                      className={`pg-sub-comment pg-sub-comment-${
                        sc.sub_comment_id
                      }`}
                      key={sc.sub_comment_id}
                    >
                      <div className="pg-sub-comment-head">
                        {sc.profile_image === "" ? (
                          <div
                            className="pg-sub-commenter-image img"
                            style={{
                              backgroundImage:
                                "url(" +
                                this.props.root +
                                "public/images/profile_pic/avatar.jpg)"
                            }}
                          >
                            <img className="img-decoy" alt={sc.username} />
                          </div>
                        ) : (
                          <div
                            className="pg-sub-commenter-image img"
                            style={{
                              backgroundImage:
                                "url(" +
                                this.props.root +
                                "public/images/profile_pic/" +
                                sc.profile_image +
                                ")"
                            }}
                          >
                            <img className="img-decoy" alt={sc.username} />
                          </div>
                        )}

                        <div className="pg-sub-commenter-profile">
                          <Link
                            to={`/user/${sc.username.replace(/ /g, "-")}-${
                              sc.user_id
                            }`}
                          >
                            <p className="pg-sub-commenter-username">
                              {sc.username}
                            </p>
                          </Link>
                          <p className="pg-sub-comment-time">
                            {" "}
                            <TimeDiff date={sc.created_at} />{" "}
                          </p>
                        </div>
                        <div className="clear-float" />
                      </div>
                      <div className="pg-sub-comment-body">
                        <p
                          className={`pg-sub-comment-in pg-sub-comment-in-${
                            sc.sub_comment_id
                          }`}
                          dangerouslySetInnerHTML={{
                            __html: sc.sub_comment
                          }}
                        />
                      </div>
                      <div className="pg-sub-comment-ft">
                        {parseInt(sc.user_id) ===
                          this.state.isLoggedInUserID && (
                          <p className="pg-sc-comment-edit">
                            <EditSVG />{" "}
                            <span
                              data-user={c.user_id}
                              data-cid={c.comment_id}
                              data-scid={sc.sub_comment_id}
                              onClick={this.setSubCommentID}
                            >
                              Edit
                            </span>
                          </p>
                        )}

                        {parseInt(sc.user_id) ===
                          this.state.isLoggedInUserID && (
                          <p className="pg-sc-comment-delete">
                            <DeleteSVG />{" "}
                            <span
                              onClick={this.subCommentDelete(
                                this.state.post.post_id,
                                sc.sub_comment_id
                              )}
                            >
                              Delete
                            </span>
                          </p>
                        )}

                        <Link
                          to={`/post/report/SubComment/${
                            this.state.post.post_id
                          }/${c.comment_id}/${sc.sub_comment_id}`}
                        >
                          <p className="pg-sub-comment-report">Report</p>
                        </Link>

                        <div className="clear-float" />
                      </div>
                    </div>
                  )
              )}
            </div>
          ))}
        </div>

        <div className="article-editor-wrapper hide">
          <span
            className="article-editor-wrapper-close"
            onClick={this.toggleArticleEditor}
          >
            <CloseSVG /> Close
          </span>

          <ArticleEditor
            apiROOT={this.props.apiROOT}
            root={this.props.root}
            post_title={this.state.post.post_title}
            post_int={this.state.post.post_int}
            post_id={this.state.post.post_id}
            setter="update"
          />
        </div>

        <div className="comment-editor-wrapper hide">
          <span
            className="comment-editor-wrapper-close"
            onClick={this.toggleCommentEditor}
          >
            <CloseSVG /> Close
          </span>

          <CommentEditor
            apiROOT={this.props.apiROOT}
            root={this.props.root}
            post_id={this.state.post.post_id}
            comment_id={this.state.commentID}
            post_title={this.state.post.post_title}
            user_id={this.state.post.user_id}
            setter={this.state.commentSetter}
          />
        </div>

        <div className="sub-comment-editor-wrapper hide">
          <span
            className="sub-comment-editor-wrapper-close"
            onClick={this.toggleSubCommentEditor}
          >
            <CloseSVG /> Close
          </span>

          <SubCommentEditor
            apiROOT={this.props.apiROOT}
            root={this.props.root}
            postID={this.state.post.post_id}
            commentID={this.state.commentID}
            subCommentID={this.state.subCommentID}
            userID={this.state.comment_userID}
            setter={this.state.subCommentSetter}
          />
        </div>
      </React.Fragment>
    );
  }
}

export default MainPost;
