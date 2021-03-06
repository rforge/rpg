% Generated by roxygen2 (4.1.0): do not edit by hand
% Please edit documentation in R/rpg.R
\name{psql}
\alias{psql}
\title{PostgreSQL shell}
\usage{
psql(psql_opts = "")
}
\arguments{
\item{psql_opts}{a character string passed to the psql command}
}
\description{
Run PostgreSQL's psql shell interactively
}
\details{
The \code{psql} function repeatedly queries for input and pipes it to
PostgreSQL's psql command. It will terminate on \code{\\q} or empty
input.

If \code{psql_opts} is an empty string, then an attempt will be made to
supply suitable options based on the current connection. If there is no
active connection, psql will fallback to complied in defaults. If
\code{psql_opts} is not an empty string, then it will be passed as-is to
psql.

You can type psql's escape commands as usual. Try \code{\?}. You
cannot use \code{\\e} or \code{\\ef} to evoke an editor. Doing strange
things with \code{\!} will likely hang the R session.

There is no way to direclty enter a database password. If one is required,
you can use a \href{http://www.postgresql.org/docs/9.1/static/libpq-pgpass.html}{password file}
or \code{\link{set_conn_defaults}}.

Unfortunately it is probably impossible to enable GNU readline support
so for example up-arrow will recall your R commands, not the psql
commands entered. You can always call psql from a terminal.
}
\author{
Timothy H. Keitt
}
\seealso{
\code{\link{set_default_password}}
}

