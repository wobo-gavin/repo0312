import sys, os
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer

lexers['php'] = PhpLexer(startinline=True, linenos=1)
lexers['php-annotations'] = PhpLexer(startinline=True, linenos=1)
primary_domain = 'php'

# -- General configuration -----------------------------------------------------

extensions = []
templates_path = ['_templates']
source_suffix = '.rst'
master_doc = 'index'

project = u'/* Replaced /* Replaced /* Replaced Guzzle */ */ */'
copyright = u'2012, Michael Dowling'
version = '3.0.0'
release = '3.0.0'

exclude_patterns = ['_build']

# -- Options for HTML output ---------------------------------------------------

# The name for this set of Sphinx documents.  If None, it defaults to
# "<project> v<release> documentation".
html_title = "/* Replaced /* Replaced /* Replaced Guzzle */ */ */ documentation"
html_short_title = "/* Replaced /* Replaced /* Replaced Guzzle */ */ */"

# Add any paths that contain custom static files (such as style sheets) here,
# relative to this directory. They are copied after the builtin static files,
# so a file named "default.css" will overwrite the builtin "default.css".
html_static_path = ['_static']

# Custom sidebar templates, maps document names to template names.
html_sidebars = {
    '**':       ['localtoc.html', 'leftbar.html', 'searchbox.html']
}

# Output file base name for HTML help builder.
htmlhelp_basename = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */doc'

# -- /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Sphinx theme setup ------------------------------------------------

sys.path.insert(0, '/Users/dowling/projects//* Replaced /* Replaced /* Replaced guzzle */ */ */_sphinx_theme')

import /* Replaced /* Replaced /* Replaced guzzle */ */ */_sphinx_theme
pygments_style = '/* Replaced /* Replaced /* Replaced guzzle */ */ */_sphinx_theme./* Replaced /* Replaced /* Replaced Guzzle */ */ */Style'
html_translator_class = '/* Replaced /* Replaced /* Replaced guzzle */ */ */_sphinx_theme.HTMLTranslator'
html_theme_path = /* Replaced /* Replaced /* Replaced guzzle */ */ */_sphinx_theme.html_theme_path()
html_theme = '/* Replaced /* Replaced /* Replaced guzzle */ */ */_sphinx_theme'

# /* Replaced /* Replaced /* Replaced Guzzle */ */ */ theme options (see theme.conf for more information)
html_theme_options = {
    "index_template": "index.html",
    "project_nav_name": "/* Replaced /* Replaced /* Replaced Guzzle */ */ */",
    "github_user": "/* Replaced /* Replaced /* Replaced guzzle */ */ */",
    "github_repo": "/* Replaced /* Replaced /* Replaced guzzle */ */ */",
    "disqus_comments_shortname": "/* Replaced /* Replaced /* Replaced guzzle */ */ */",
    "google_analytics_account": "UA-22752917-1"
}

# -- Options for LaTeX output --------------------------------------------------

latex_elements = {}

# Grouping the document tree into LaTeX files. List of tuples
# (source start file, target name, title, author, documentclass [howto/manual]).
latex_documents = [
  ('index', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */.tex', u'/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Documentation',
   u'Michael Dowling', 'manual'),
]

# -- Options for manual page output --------------------------------------------

# One entry per manual page. List of tuples
# (source start file, name, description, authors, manual section).
man_pages = [
    ('index', '/* Replaced /* Replaced /* Replaced guzzle */ */ */', u'/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Documentation',
     [u'Michael Dowling'], 1)
]

# If true, show URL addresses after external links.
#man_show_urls = False

# -- Options for Texinfo output ------------------------------------------------

# Grouping the document tree into Texinfo files. List of tuples
# (source start file, target name, title, author,
#  dir menu entry, description, category)
texinfo_documents = [
  ('index', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */', u'/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Documentation',
   u'Michael Dowling', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */', 'One line description of project.',
   'Miscellaneous'),
]
