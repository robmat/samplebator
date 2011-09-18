#import "ChooseCvVC.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"
#import "ChooseCvTC.h"

@implementation ChooseCvVC

@synthesize jobTitleLbl, tableView;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	tableVC = [[ChooseCvTVC alloc] initWithStyle:UITableViewStylePlain];
	tableVC.tableView = tableView;
	[tableVC viewDidLoad];
}

- (void)dealloc {
    [super dealloc];
	[jobTitleLbl release];
	[tableView release];
}

@end

@implementation ChooseCvTVC

@synthesize navCntrl, doc;

- (void)viewDidLoad {
    [super viewDidLoad];
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listcvs"]];
	req.delegate = self;
	[req startAsynchronous];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 57;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    //NSArray* nodes = [doc nodesForXPath:@"/RunSimpleSearch/Job" error:nil];
	return [nodes count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    ChooseCvTC *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"SearchResultsTC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (ChooseCvTC *) currentObject;
                break;
            }
        }
    }
	return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	CXMLDocument* xmlDoc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
}

- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)dealloc {
    [super dealloc];
	[navCntrl release];
	[doc release];
}

@end