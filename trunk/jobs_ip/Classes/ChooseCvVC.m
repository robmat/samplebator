#import "ChooseCvVC.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"
#import "ASIFormDataRequest.h"
#import "ApplyVC.h"

@implementation ChooseCvVC

@synthesize jobTitleLbl, tableView, jobId, nextBtn;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)enableNext {
	nextBtn.hidden = NO;
}

- (void)nextAction: (id) sender {
	ApplyVC* avc = [[ApplyVC alloc] init];
	[self.navigationController pushViewController:avc animated:YES];
	ChooseCvTC* cell = (ChooseCvTC*) [tableView cellForRowAtIndexPath:[tableView indexPathForSelectedRow]];
	avc.jobTitle.text = jobTitleLbl.text;
	avc.titlLbl.text = cell.titlLbl.text;
	avc.dateLbl.text = cell.dateLbl.text;
	avc.descLbl.text = cell.descLbl.text;
	avc.cvId = [NSString stringWithFormat:@"%i", cell.tag];
	avc.jobId = jobId;
	[avc release];
}

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	tableVC = [[ChooseCvTVC alloc] initWithStyle:UITableViewStylePlain];
	tableVC.navCntrl = self.navigationController;
	tableVC.tableView = tableView;
	tableVC.parentVC = self;
	[tableVC viewDidLoad];
}

- (void)dealloc {
    [super dealloc];
	[jobTitleLbl release];
	[tableView release];
	[jobId release];
	[nextBtn release];
}

@end

@implementation ChooseCvTVC

@synthesize navCntrl, doc, parentVC;

- (void)viewDidLoad {
    [super viewDidLoad];
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listcvs"]];
	[req setRequestMethod:@"POST"];
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
    int nodes = [[doc nodesForXPath:@"/CVList/CV" error:nil] count];
	return nodes;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    ChooseCvTC *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"ChooseCvTC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (ChooseCvTC *) currentObject;
                break;
            }
        }
    }
	NSString* title = [[[doc nodesForXPath:@"/CVList/CV/ResumeTitle" error:nil] objectAtIndex:indexPath.row] stringValue];
	NSString* date = [[[doc nodesForXPath:@"/CVList/CV/ActivationDate" error:nil] objectAtIndex:indexPath.row] stringValue];
	NSString* summary = [[[doc nodesForXPath:@"/CVList/CV/Summary" error:nil] objectAtIndex:indexPath.row] stringValue];
	NSString* cvIdStr = [[[doc nodesForXPath:@"/CVList/CV/ResumeSID" error:nil] objectAtIndex:indexPath.row] stringValue];
	cell.titlLbl.text = title;
	cell.descLbl.text = summary;
	NSDateFormatter* dateFormatter = [[[NSDateFormatter alloc] init] autorelease];
    [dateFormatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    NSDate* dateObj = [dateFormatter dateFromString:date];
	[dateFormatter setDateFormat:@"dd-MM-yyyy"];
	cell.dateLbl.text = [dateFormatter stringFromDate:dateObj];
	cell.tag = [cvIdStr intValue];
	return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[[tableView cellForRowAtIndexPath:indexPath] setSelected:NO];
	for (int i = 0; i < [tableView numberOfRowsInSection:0]; i++) {
		ChooseCvTC * cell = (ChooseCvTC *) [tableView cellForRowAtIndexPath:[NSIndexPath indexPathForRow:i inSection:0]];
		cell.imageV.image = [UIImage imageNamed:@"blank.png"];
	}
	ChooseCvTC * cell = (ChooseCvTC *) [tableView cellForRowAtIndexPath:indexPath];
	cell.imageV.image = [UIImage imageNamed:@"blue_tick.png"];
	cvId = cell.tag;
	[parentVC enableNext];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	[self.tableView reloadData];
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
	[parentVC release];
}

@end

@implementation ChooseCvTC

@synthesize titlLbl, descLbl, dateLbl, imageV;

- (void)dealloc {
    [super dealloc];
	[titlLbl release];
	[descLbl release];
	[dateLbl release];
	[imageV release];
}

@end