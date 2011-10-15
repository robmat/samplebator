#import "MyCVs.h"
#import "ASIHTTPRequest.h"
#import "ChooseCvVC.h"

@implementation MyCVs

@synthesize tableView;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {

    }
    return self;
}
- (void)viewDidLoad {
    [super viewDidLoad];
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listcvs"]];
	req.delegate = self;
	[req startAsynchronous];
	backBtn.hidden = YES;
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	[self.tableView reloadData];
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [[doc nodesForXPath:@"/CVList/CV" error:nil] count];
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
	cell.imageV.image = [UIImage imageNamed:@"my_cvs_del_btn.png"];
	if (indexPath.row % 2 != 0) {
		cell.backgroundColor = [UIColor colorWithRed:0.921 green:0.921 blue:0.913 alpha:1];
	}
	return cell;
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 57;
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[doc release];
}

@end
