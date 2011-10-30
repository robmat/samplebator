#import "SharepointListTVC.h"
#import "WebViewVC.h"

@implementation SharepointListTVC

@synthesize listsData, keysArr, navCntrl, datesData;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.keysArr = [listsData allKeys];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [listsData count];
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	cell.backgroundColor = [UIColor colorWithRed:0 green:0.62890625 blue:0.82421875 alpha:1];
	cell.textLabel.font = [UIFont fontWithName:@"Helvetica" size:14];
	cell.textLabel.textColor = [UIColor whiteColor];
}
- (UITableViewCell *)tableView:(UITableView *)tableView_ cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    SharepointListCell* cell = nil;
	if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"SharepointListVC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (SharepointListCell*) currentObject;
                break;
            }
        }
    }
    NSString* key = [keysArr objectAtIndex:indexPath.row];
	cell.titleLbl.text = [listsData objectForKey:key];
	cell.dateLbl.text = [datesData objectForKey:key];
    cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
	return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[[tableView cellForRowAtIndexPath:indexPath] setSelected:NO];
	NSString* key = [keysArr objectAtIndex:indexPath.row];
	WebViewVC* wvvc = [[WebViewVC alloc] initWithNibName:nil bundle:nil];
	wvvc.url = key;
	[self.navCntrl pushViewController:wvvc animated:YES];
	[wvvc release];
}
- (void)dealloc {
    [super dealloc];
	[listsData release];
	[navCntrl release];
	[datesData release];
}

@end

@implementation SharepointListCell

@synthesize titleLbl, dateLbl;

- (void)dealloc {
	[super dealloc];
	[titleLbl release];
	[dateLbl release];
}

@end

