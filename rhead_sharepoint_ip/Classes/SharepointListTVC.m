#import "SharepointListTVC.h"
#import "WebViewVC.h"

@implementation SharepointListTVC

@synthesize listsData, keysArr, navCntrl;

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
}

- (UITableViewCell *)tableView:(UITableView *)tableView_ cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView_ dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier] autorelease];
    }
    NSString* key = [keysArr objectAtIndex:indexPath.row];
	cell.textLabel.text = [listsData objectForKey:key];
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
}

@end

